<?php

namespace App\Actions\Tec;

use App\Models\Checkin;
use App\Models\Transfer;
use App\Models\Adjustment;

class OrderData
{
    public function __invoke($form, $model)
    {
        $checkin = ($model instanceof Checkin);
        $transfer = ($model instanceof Transfer);
        $adjustment = ($model instanceof Adjustment);
        $form['items'] = collect($form['items'])->transform(function ($item, $index) use ($form, $checkin, $adjustment, $transfer) {
            $item['serials'] = [];
            if ($adjustment && ! empty($item['selected']['serials'])) {
                $item['serials'] = $item['selected']['serials'];
            } elseif ($checkin && ! empty($item['selected']['serials'])) {
                $serials = $item['selected']['serials'];
                foreach ($serials as $serial) {
                    if (isset($serial['till']) && ! empty($serial['till'])) {
                        for ($i = $serial['number']; $i < $serial['till']; $i++) {
                            $item['serials'][] = $serial['number'] + $i;
                        }
                    } else {
                        $item['serials'][] = isset($serial['number']) && ! empty($serial['number']) ? $serial['number'] : $serial;
                    }
                }
            } else {
                if (! empty($item['selected']['serials'])) {
                    $item['serials'] = $item['selected']['serials'];
                }
            }

            $item['variations'] = $item['selected']['variations'] ?? [];
            if (! empty($item['variations'])) {
                $item['weight'] = 0;
                $item['quantity'] = 0;
                $track_weight = get_settings('track_weight');
                foreach ($item['variations'] as &$variation) {
                    if ($track_weight && ($item['track_weight'] ?? null)) {
                        $item['weight'] += $variation['weight'];
                    }
                    if ($item['track_quantity'] ?? true) {
                        $item['quantity'] += $variation['quantity'];
                    }
                    unset($variation['old_quantity']);
                }
            }
            unset($item['selected']);
            $item['order'] = $index;
            if ($adjustment) {
                $item['type'] = $form['type'];
            }
            if ($transfer) {
                $item['to_warehouse_id'] = $form['to_warehouse_id'];
                $item['from_warehouse_id'] = $form['from_warehouse_id'];
            } else {
                $item['warehouse_id'] = $form['warehouse_id'];
            }
            $item['draft'] = $form['draft'];
            unset($item['old_quantity']);

            return $item;
        });

        return $form;
    }
}
