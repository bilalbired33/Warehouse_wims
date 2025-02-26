<?php

namespace App\Listeners;

use App\Events\CheckinEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckinEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $checkin;

    public $track_weight;

    public function failed(CheckinEvent $event, $exception)
    {
        Log::error('CheckinEvent failed!', ['Error' => $exception, 'Checkin' => $event->checkin]);
    }

    public function handle(CheckinEvent $event)
    {
        $this->track_weight = get_settings('track_weight');
        $this->{$event->method}($event);
    }

    private function created(CheckinEvent $event)
    {
        if (! $event->checkin->draft) {
            $this->setStock($event->checkin);
        }
    }

    private function deleted(CheckinEvent $event)
    {
        if (! $event->checkin->draft) {
            $this->setStock($event->checkin, true);
        }
    }

    private function restored(CheckinEvent $event)
    {
        if (! $event->checkin->draft) {
            $this->setStock($event->checkin);
        }
    }

    private function setStock($checkin, $decrease = false)
    {
        foreach ($checkin->items as $item) {
            if ($item->item->track_quantity || $item->item->track_weight) {
                $stock = $item->stock()->main()->ofWarehouse($checkin->warehouse_id)->first();
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
                            'warehouse_id' => $checkin->warehouse_id,
                            'type'         => ($decrease ? 'Editing' : 'Updating') . ' Checkin Item',
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
                        'warehouse_id'   => $checkin->warehouse_id,
                        'rack_location'  => $item->item->rack_location,
                        'alert_quantity' => $item->item->alert_quantity,
                    ]);
                    $item->item->stockTrails()->create([
                        'variation_id' => null,
                        'weight'       => $weight,
                        'quantity'     => $quantity,
                        'item_id'      => $item->item_id,
                        'unit_id'      => $item->unit_id,
                        'warehouse_id' => $checkin->warehouse_id,
                        'type'         => ($decrease ? 'Editing' : 'Updating') . ' Checkin Item',
                    ])->referencesObject($item);
                }
                if ($item->variations && $item->variations->isNotEmpty()) {
                    foreach ($item->variations as $variation) {
                        $variationStock = $variation->stock()->ofWarehouse($checkin->warehouse_id)->first();
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
                                    'warehouse_id' => $checkin->warehouse_id,
                                    'type'         => ($decrease ? 'Editing' : 'Updating') . ' Checkin Item Variant',
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
                                'warehouse_id'   => $checkin->warehouse_id,
                                'rack_location'  => $item->item->rack_location,
                                'alert_quantity' => $item->item->alert_quantity,
                            ]);
                            $item->item->stockTrails()->create([
                                'weight'       => $weight,
                                'quantity'     => $quantity,
                                'item_id'      => $item->item_id,
                                'unit_id'      => $item->unit_id,
                                'variation_id' => $variation->id,
                                'warehouse_id' => $checkin->warehouse_id,
                                'type'         => ($decrease ? 'Editing' : 'Updating') . ' Checkin Item Variant',
                            ])->referencesObject($item);
                        }
                    }
                }
            }
        }
    }

    private function updated(CheckinEvent $event)
    {
        if (! $event->original->draft) {
            $this->setStock($event->original, true);
        }
        if (! $event->checkin->draft) {
            $this->setStock($event->checkin);
        }
    }
}
