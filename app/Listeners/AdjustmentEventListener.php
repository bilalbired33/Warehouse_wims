<?php

namespace App\Listeners;

use App\Events\AdjustmentEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdjustmentEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $adjustment;

    public $track_weight;

    public function failed(AdjustmentEvent $event, $exception)
    {
        Log::error('AdjustmentEvent failed!', ['Error' => $exception, 'Adjustment' => $event->adjustment]);
    }

    public function handle(AdjustmentEvent $event)
    {
        $this->track_weight = get_settings('track_weight');
        $this->{$event->method}($event);
    }

    private function created(AdjustmentEvent $event)
    {
        if (! $event->adjustment->draft) {
            $this->setStock($event->adjustment, $event->adjustment->type != 'Addition');
        }
    }

    private function deleted(AdjustmentEvent $event)
    {
        if (! $event->adjustment->draft) {
            $this->setStock($event->adjustment, $event->adjustment->type == 'Addition');
        }
    }

    private function restored(AdjustmentEvent $event)
    {
        if (! $event->adjustment->draft) {
            $this->setStock($event->adjustment, $event->adjustment->type != 'Addition');
        }
    }

    private function setStock($adjustment, $decrease = false)
    {
        foreach ($adjustment->items as $item) {
            if ($item->item->track_quantity || $item->item->track_weight) {
                $stock = $item->stock()->main()->ofWarehouse($adjustment->warehouse_id)->first();
                if ($stock) {
                    $weight = $stock->weight;
                    $quantity = $stock->quantity;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $decrease ? $stock->quantity - $base_quantity : $stock->quantity + $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $decrease ? $stock->weight - $item->weight : $stock->weight + $item->weight;
                    }
                    if ($stock->quantity != $quantity || $stock->weight != $weight) {
                        $stock->update(['quantity' => $quantity, 'weight' => $weight]);
                        // event(new \App\Events\StockEvent($item, $quantity, $weight));
                        $item->item->stockTrails()->create([
                            'variation_id' => null,
                            'weight'       => $weight,
                            'quantity'     => $quantity,
                            'item_id'      => $item->item_id,
                            'unit_id'      => $item->unit_id,
                            'warehouse_id' => $adjustment->warehouse_id,
                            'type'         => ($decrease ? 'Editing' : 'Updating') . ' Adjustment Item',
                        ])->referencesObject($item);
                    }
                } else {
                    $weight = 0;
                    $quantity = 0;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $decrease ? 0 - $base_quantity : $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $decrease ? 0 - $item->weight : $item->weight;
                    }
                    $item->item->stock()->create([
                        'variation_id'   => null,
                        'weight'         => $weight,
                        'quantity'       => $quantity,
                        'account_id'     => getAccountId(),
                        'warehouse_id'   => $adjustment->warehouse_id,
                        'rack_location'  => $item->item->rack_location,
                        'alert_quantity' => $item->item->alert_quantity,
                    ]);
                    $item->item->stockTrails()->create([
                        'variation_id' => null,
                        'weight'       => $weight,
                        'quantity'     => $quantity,
                        'item_id'      => $item->item_id,
                        'unit_id'      => $item->unit_id,
                        'warehouse_id' => $adjustment->warehouse_id,
                        'type'         => ($decrease ? 'Editing' : 'Updating') . ' Adjustment Item',
                    ])->referencesObject($item);
                }
                if ($item->variations && $item->variations->isNotEmpty()) {
                    foreach ($item->variations as $variation) {
                        $variationStock = $variation->stock()->ofWarehouse($adjustment->warehouse_id)->first();
                        if ($variationStock) {
                            $weight = $variationStock->weight;
                            $quantity = $variationStock->quantity;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $decrease ? $variationStock->quantity - $base_quantity : $variationStock->quantity + $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $decrease ? $variationStock->weight - $variation->pivot->weight : $variationStock->weight + $variation->pivot->weight;
                            }
                            if ($variationStock->quantity != $quantity || $variationStock->weight != $weight) {
                                $variationStock->update(['quantity' => $quantity, 'weight' => $weight]);
                                // event(new \App\Events\StockEvent($item, $quantity, $weight, $variation->id));
                                $item->item->stockTrails()->create([
                                    'weight'       => $weight,
                                    'quantity'     => $quantity,
                                    'item_id'      => $item->item_id,
                                    'unit_id'      => $item->unit_id,
                                    'variation_id' => $variation->id,
                                    'warehouse_id' => $adjustment->warehouse_id,
                                    'type'         => ($decrease ? 'Editing' : 'Updating') . ' Adjustment Item',
                                ])->referencesObject($item);
                            }
                        } else {
                            $weight = 0;
                            $quantity = 0;
                            if ($item->item->track_quantity) {
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $decrease ? 0 - $base_quantity : $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $decrease ? 0 - $variation->pivot->weight : $variation->pivot->weight;
                            }
                            $variation->stock()->create([
                                'weight'         => $weight,
                                'quantity'       => $quantity,
                                'account_id'     => getAccountId(),
                                'item_id'        => $item->item_id,
                                'warehouse_id'   => $adjustment->warehouse_id,
                                'rack_location'  => $item->item->rack_location,
                                'alert_quantity' => $item->item->alert_quantity,
                            ]);
                            $item->item->stockTrails()->create([
                                'weight'       => $weight,
                                'quantity'     => $quantity,
                                'item_id'      => $item->item_id,
                                'unit_id'      => $item->unit_id,
                                'variation_id' => $variation->id,
                                'warehouse_id' => $adjustment->warehouse_id,
                                'type'         => ($decrease ? 'Editing' : 'Updating') . ' Adjustment Item',
                            ])->referencesObject($item);
                        }
                    }
                }
            }
        }
    }

    private function updated(AdjustmentEvent $event)
    {
        if (! $event->original->draft) {
            $this->setStock($event->original, $event->original->type == 'Addition');
        }
        if (! $event->adjustment->draft) {
            $this->setStock($event->adjustment, $event->adjustment->type != 'Addition');
        }
    }
}
