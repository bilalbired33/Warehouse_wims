<?php

namespace App\Listeners;

use App\Events\TransferEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $track_weight;

    public $transfer;

    public function failed(TransferEvent $event, $exception)
    {
        Log::error('TransferEvent failed!', ['Error' => $exception, 'Transfer' => $event->transfer]);
    }

    public function handle(TransferEvent $event)
    {
        $this->track_weight = get_settings('track_weight');
        $this->{$event->method}($event);
    }

    private function created(TransferEvent $event)
    {
        if (! $event->transfer->draft) {
            $this->setStock($event->transfer);
        }
    }

    private function deleted(TransferEvent $event)
    {
        if (! $event->transfer->draft) {
            $this->setStock($event->transfer, true);
        }
    }

    private function restored(TransferEvent $event)
    {
        if (! $event->transfer->draft) {
            $this->setStock($event->transfer);
        }
    }

    private function setStock($transfer, $moveBack = false)
    {
        foreach ($transfer->items as $item) {
            if ($item->item->track_quantity || $item->item->track_weight) {
                $fromStock = $item->stock()->main()->ofWarehouse($transfer->from_warehouse_id)->first();
                if ($fromStock) {
                    $weight = $fromStock->weight;
                    $quantity = $fromStock->quantity;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $moveBack ? $fromStock->quantity + $base_quantity : $fromStock->quantity - $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $moveBack ? $fromStock->weight + $item->weight : $fromStock->weight - $item->weight;
                    }
                    if ($fromStock->quantity != $quantity || $fromStock->weight != $weight) {
                        $fromStock->update(['quantity' => $quantity, 'weight' => $weight]);
                        // event(new \App\Events\StockEvent($item, $quantity, $weight, null, $transfer->from_warehouse_id));
                        $item->item->stockTrails()->create([
                            'variation_id' => null,
                            'weight'       => $weight,
                            'quantity'     => $quantity,
                            'item_id'      => $item->item_id,
                            'unit_id'      => $item->unit_id,
                            'warehouse_id' => $transfer->from_warehouse_id,
                            'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                        ])->referencesObject($item);
                    }
                } else {
                    $weight = 0;
                    $quantity = 0;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $moveBack ? $base_quantity : 0 - $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $moveBack ? $item->weight : 0 - $item->weight;
                    }
                    $item->item->stock()->create([
                        'variation_id'   => null,
                        'weight'         => $weight,
                        'quantity'       => $quantity,
                        'account_id'     => getAccountId(),
                        'rack_location'  => $item->item->rack_location,
                        'alert_quantity' => $item->item->alert_quantity,
                        'warehouse_id'   => $transfer->from_warehouse_id,
                    ]);
                    $item->item->stockTrails()->create([
                        'variation_id' => null,
                        'weight'       => $weight,
                        'quantity'     => $quantity,
                        'item_id'      => $item->item_id,
                        'unit_id'      => $item->unit_id,
                        'warehouse_id' => $transfer->from_warehouse_id,
                        'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                    ])->referencesObject($item);
                }
                if ($item->variations && $item->variations->isNotEmpty()) {
                    foreach ($item->variations as $variation) {
                        $variationStock = $variation->stock()->ofWarehouse($transfer->from_warehouse_id)->first();
                        if ($variationStock) {
                            $weight = $variationStock->weight;
                            $quantity = $variationStock->quantity;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $moveBack ? $variationStock->quantity + $base_quantity : $variationStock->quantity - $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $moveBack ? $variationStock->weight + $variation->pivot->weight : $variationStock->weight - $variation->pivot->weight;
                            }
                            if ($variationStock->quantity != $quantity || $variationStock->weight != $weight) {
                                $variationStock->update(['quantity' => $quantity, 'weight' => $weight]);
                                // event(new \App\Events\StockEvent($item, $quantity, $weight, $variation->id, $transfer->from_warehouse_id));
                                $item->item->stockTrails()->create([
                                    'weight'       => $weight,
                                    'quantity'     => $quantity,
                                    'item_id'      => $item->item_id,
                                    'unit_id'      => $item->unit_id,
                                    'variation_id' => $variation->id,
                                    'warehouse_id' => $transfer->from_warehouse_id,
                                    'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                                ])->referencesObject($item);
                            }
                        } else {
                            $weight = 0;
                            $quantity = 0;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $moveBack ? $base_quantity : 0 - $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $moveBack ? $variation->pivot->weight : 0 - $variation->pivot->weight;
                            }
                            $variation->stock()->create([
                                'weight'         => $weight,
                                'quantity'       => $quantity,
                                'account_id'     => getAccountId(),
                                'item_id'        => $item->item_id,
                                'rack_location'  => $item->item->rack_location,
                                'alert_quantity' => $item->item->alert_quantity,
                                'warehouse_id'   => $transfer->from_warehouse_id,
                            ]);
                            $item->item->stockTrails()->create([
                                'weight'       => $weight,
                                'quantity'     => $quantity,
                                'item_id'      => $item->item_id,
                                'unit_id'      => $item->unit_id,
                                'variation_id' => $variation->id,
                                'warehouse_id' => $transfer->from_warehouse_id,
                                'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                            ])->referencesObject($item);
                        }
                    }
                }

                $toStock = $item->stock()->main()->ofWarehouse($transfer->to_warehouse_id)->first();
                if ($toStock) {
                    $weight = $toStock->weight;
                    $quantity = $toStock->quantity;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $moveBack ? $toStock->quantity - $base_quantity : $toStock->quantity + $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $moveBack ? $toStock->weight - $item->weight : $toStock->weight + $item->weight;
                    }
                    if ($toStock->quantity != $quantity || $toStock->weight != $weight) {
                        $toStock->update(['quantity' => $quantity, 'weight' => $weight]);
                        // event(new \App\Events\StockEvent($item, $quantity, $weight, null, $transfer->to_warehouse_id));
                        $item->item->stockTrails()->create([
                            'variation_id' => null,
                            'weight'       => $weight,
                            'quantity'     => $quantity,
                            'item_id'      => $item->item_id,
                            'unit_id'      => $item->unit_id,
                            'warehouse_id' => $transfer->to_warehouse_id,
                            'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                        ])->referencesObject($item);
                    }
                } else {
                    $weight = 0;
                    $quantity = 0;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $moveBack ? 0 - $base_quantity : $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $moveBack ? 0 - $item->weight : $item->weight;
                    }
                    $item->item->stock()->create([
                        'variation_id'   => null,
                        'weight'         => $weight,
                        'quantity'       => $quantity,
                        'account_id'     => getAccountId(),
                        'warehouse_id'   => $transfer->to_warehouse_id,
                        'rack_location'  => $item->item->rack_location,
                        'alert_quantity' => $item->item->alert_quantity,
                    ]);
                    $item->item->stockTrails()->create([
                        'variation_id' => null,
                        'weight'       => $weight,
                        'quantity'     => $quantity,
                        'item_id'      => $item->item_id,
                        'unit_id'      => $item->unit_id,
                        'warehouse_id' => $transfer->to_warehouse_id,
                        'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                    ])->referencesObject($item);
                }
                if ($item->variations && $item->variations->isNotEmpty()) {
                    foreach ($item->variations as $variation) {
                        $variationStock = $variation->stock()->ofWarehouse($transfer->to_warehouse_id)->first();
                        if ($variationStock) {
                            $weight = $variationStock->weight;
                            $quantity = $variationStock->quantity;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $moveBack ? $variationStock->quantity - $base_quantity : $variationStock->quantity + $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $moveBack ? $variationStock->weight - $variation->pivot->weight : $variationStock->weight + $variation->pivot->weight;
                            }
                            if ($variationStock->quantity != $quantity || $variationStock->weight != $weight) {
                                $variationStock->update(['quantity' => $quantity, 'weight' => $weight]);
                                // event(new \App\Events\StockEvent($item, $quantity, $weight, $variation->id, $transfer->to_warehouse_id));
                                $item->item->stockTrails()->create([
                                    'weight'       => $weight,
                                    'quantity'     => $quantity,
                                    'item_id'      => $item->item_id,
                                    'unit_id'      => $item->unit_id,
                                    'variation_id' => $variation->id,
                                    'warehouse_id' => $transfer->to_warehouse_id,
                                    'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                                ])->referencesObject($item);
                            }
                        } else {
                            $weight = 0;
                            $quantity = 0;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $moveBack ? 0 - $base_quantity : $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $moveBack ? 0 - $variation->pivot->weight : $variation->pivot->weight;
                            }
                            $variation->stock()->create([
                                'weight'         => $weight,
                                'quantity'       => $quantity,
                                'account_id'     => getAccountId(),
                                'item_id'        => $item->item_id,
                                'warehouse_id'   => $transfer->to_warehouse_id,
                                'rack_location'  => $item->item->rack_location,
                                'alert_quantity' => $item->item->alert_quantity,
                            ]);
                            $item->item->stockTrails()->create([
                                'weight'       => $weight,
                                'quantity'     => $quantity,
                                'item_id'      => $item->item_id,
                                'unit_id'      => $item->unit_id,
                                'variation_id' => $variation->id,
                                'warehouse_id' => $transfer->to_warehouse_id,
                                'type'         => ($moveBack ? 'Editing' : 'Updating') . ' Transfer Item',
                            ])->referencesObject($item);
                        }
                    }
                }
            }
        }
    }

    private function updated(TransferEvent $event)
    {
        if (! $event->original->draft) {
            $this->setStock($event->original, true);
        }
        if (! $event->transfer->draft) {
            $this->setStock($event->transfer);
        }
    }
}
