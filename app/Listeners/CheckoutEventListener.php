<?php

namespace App\Listeners;

use App\Models\Unit;
use App\Events\CheckoutEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckoutEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public $checkout;

    public $track_weight;

    public function failed(CheckoutEvent $event, $exception)
    {
        Log::error('CheckoutEvent failed!', ['Error' => $exception, 'Checkout' => $event->checkout]);
    }

    public function handle(CheckoutEvent $event)
    {
        $this->track_weight = get_settings('track_weight');
        $this->{$event->method}($event);
    }

    private function created(CheckoutEvent $event)
    {
        if (! $event->checkout->draft) {
            $this->setStock($event->checkout);
        }
    }

    private function deleted(CheckoutEvent $event)
    {
        if (! $event->checkout->draft) {
            $this->setStock($event->checkout, true);
        }
    }

    private function restored(CheckoutEvent $event)
    {
        if (! $event->checkout->draft) {
            $this->setStock($event->checkout);
        }
    }

    private function setStock($checkout, $increase = false)
    {
        foreach ($checkout->items as $item) {
            if ($item->item->track_quantity || $item->item->track_weight) {
                $stock = $item->stock()->main()->ofWarehouse($checkout->warehouse_id)->first();
                if ($stock) {
                    $weight = $stock->weight;
                    $quantity = $stock->quantity;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $increase ? $stock->quantity + $base_quantity : $stock->quantity - $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $increase ? $stock->weight + $item->weight : $stock->weight - $item->weight;
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
                            'warehouse_id' => $checkout->warehouse_id,
                            'type'         => ($increase ? 'Editing' : 'Updating') . ' Checkout Item',
                        ])->referencesObject($item);
                    }
                } else {
                    $weight = 0;
                    $quantity = 0;
                    if ($item->item->track_quantity) {
                        $base_quantity = convert_to_base_quantity($item->quantity, $item->unit);
                        $quantity = $increase ? $base_quantity : 0 - $base_quantity;
                    }
                    if ($this->track_weight && $item->item->track_weight) {
                        $weight = $increase ? $item->weight : 0 - $item->weight;
                    }
                    $item->item->stock()->create([
                        'variation_id'   => null,
                        'weight'         => $weight,
                        'quantity'       => $quantity,
                        'account_id'     => getAccountId(),
                        'warehouse_id'   => $checkout->warehouse_id,
                        'rack_location'  => $item->item->rack_location,
                        'alert_quantity' => $item->item->alert_quantity,
                    ]);
                    $item->item->stockTrails()->create([
                        'variation_id' => null,
                        'weight'       => $weight,
                        'quantity'     => $quantity,
                        'item_id'      => $item->item_id,
                        'unit_id'      => $item->unit_id,
                        'warehouse_id' => $checkout->warehouse_id,
                        'type'         => ($increase ? 'Editing' : 'Updating') . ' Checkout Item',
                    ])->referencesObject($item);
                }
                if ($item->variations && $item->variations->isNotEmpty()) {
                    foreach ($item->variations as $variation) {
                        $variationStock = $variation->stock()->ofWarehouse($checkout->warehouse_id)->first();
                        if ($variationStock) {
                            $weight = $variationStock->weight;
                            $quantity = $variationStock->quantity;
                            if ($item->item->track_quantity) {
                                // $unit          = $variation->pivot->unit_id ? Unit::find($variation->pivot->unit_id) : null;
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $increase ? $variationStock->quantity + $base_quantity : $variationStock->quantity - $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $increase ? $variationStock->weight + $variation->pivot->weight : $variationStock->weight - $variation->pivot->weight;
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
                                    'warehouse_id' => $checkout->warehouse_id,
                                    'type'         => ($increase ? 'Editing' : 'Updating') . ' Checkout Item Variant',
                                ])->referencesObject($item);
                            }
                        } else {
                            $weight = 0;
                            $quantity = 0;
                            if ($item->item->track_quantity) {
                                // $unit          = $variation->pivot->unit_id ? Unit::find($variation->pivot->unit_id) : null;
                                $base_quantity = convert_to_base_quantity($variation->pivot->quantity, $variation->unit);
                                $quantity = $increase ? $base_quantity : 0 - $base_quantity;
                            }
                            if ($this->track_weight && $item->item->track_weight) {
                                $weight = $increase ? $variation->pivot->weight : 0 - $variation->pivot->weight;
                            }
                            $variation->stock()->create([
                                'weight'         => $weight,
                                'quantity'       => $quantity,
                                'account_id'     => getAccountId(),
                                'item_id'        => $item->item_id,
                                'warehouse_id'   => $checkout->warehouse_id,
                                'rack_location'  => $item->item->rack_location,
                                'alert_quantity' => $item->item->alert_quantity,
                            ]);
                            $item->item->stockTrails()->create([
                                'weight'       => $weight,
                                'quantity'     => $quantity,
                                'item_id'      => $item->item_id,
                                'unit_id'      => $item->unit_id,
                                'variation_id' => $variation->id,
                                'warehouse_id' => $checkout->warehouse_id,
                                'type'         => ($increase ? 'Editing' : 'Updating') . ' Checkout Item Variant',
                            ])->referencesObject($item);
                        }
                    }
                }
            }
        }
    }

    private function updated(CheckoutEvent $event)
    {
        if (! $event->original->draft) {
            $this->setStock($event->original, true);
        }
        if (! $event->checkout->draft) {
            $this->setStock($event->checkout);
        }
    }
}
