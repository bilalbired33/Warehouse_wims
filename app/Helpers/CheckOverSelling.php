<?php

namespace App\Helpers;

use App\Models\Item;
use App\Models\Variation;

class CheckOverSelling
{
    public function check($order_items, $warehouse_id)
    {
        $error = [];
        $items = Item::whereIn('id', collect($order_items)->pluck('item_id'))->with(['unit.subunits', 'stock' => fn ($q) => $q->ofWarehouse($warehouse_id)])->get();
        foreach ($order_items as $key => $order_item) {
            $old_quantity = 0;
            $base_quantity = 0;
            $unit = null;
            $item = $items->where('id', $order_item['item_id'])->first();
            if (isset($order_item['selected']) && isset($order_item['selected']['variations']) && ! empty($order_item['selected']['variations'])) {
                foreach ($order_item['selected']['variations'] as $order_variation) {
                    $quantity = $order_variation['quantity'] + 0;
                    $old_quantity = isset($order_variation['old_quantity']) ? (float) $order_variation['old_quantity'] : 0;
                    $quantity = $old_quantity ? $quantity - $old_quantity : $quantity;
                    $variation = Variation::with(['stock' => fn ($q) => $q->ofWarehouse($warehouse_id)])->find($order_variation['variation_id']);
                    $unit = $order_variation['unit_id'] ? $item->unit->subunits->where('id', $order_variation['unit_id'])->first() : null;
                    $variationStock = $variation->stock->where('warehouse_id', $warehouse_id)->first();
                    $base_quantity = $unit ? convert_to_base_quantity($quantity, $unit) : $quantity;
                    $meta = [];
                    foreach ($variation->meta as $variant => $option) {
                        $meta[] = $variant . ': ' . $option;
                    }
                    if (! $variationStock) {
                        $error["items.{$key}.quantity"] = __choice('{name} ({variant}) do not have {quantity} in stock, available quantity {available}.', ['name' => $order_item['name'], 'quantity' => $quantity . ' ' . ($unit ? $unit->name : $item->unit?->name), 'available' => '0 ' . $item->unit?->name, 'variant' => implode(', ', $meta)]);
                    } elseif ($base_quantity && $variationStock->quantity < $base_quantity) {
                        $error["items.{$key}.quantity"] = __choice('{name} ({variant}) do not have {quantity} in stock, available quantity {available}.', ['name' => $order_item['name'], 'quantity' => $quantity . ' ' . ($unit ? $unit->name : $item->unit?->name), 'available' => ((float) $variationStock->quantity) . ' ' . $item->unit?->name, 'variant' => implode(', ', $meta)]);
                    }
                }
            } else {
                $quantity = $order_item['quantity'] + 0;
                $old_quantity = isset($order_item['old_quantity']) ? (float) $order_item['old_quantity'] : 0;
                $quantity = $old_quantity ? $quantity - $old_quantity : $quantity;
                $unit = $order_item['unit_id'] ? $item->unit->subunits->where('id', $order_item['unit_id'])->first() : null;
                $stock = $item->stock->where('warehouse_id', $warehouse_id)->first();
                $base_quantity = $unit ? convert_to_base_quantity($quantity, $unit) : $quantity;
                if (! $stock) {
                    $error["items.{$key}.quantity"] = __choice('{name} do not have {quantity} in stock, available quantity {available}.', ['name' => $order_item['name'], 'quantity' => $quantity . ' ' . ($unit ? $unit->name : $item->unit?->name), 'available' => '0 ' . $item->unit?->name]);
                } elseif ($base_quantity && $stock->quantity < $base_quantity) {
                    $error["items.{$key}.quantity"] = __choice('{name} do not have {quantity} in stock, available quantity {available}.', ['name' => $order_item['name'], 'quantity' => $quantity . ' ' . ($unit ? $unit->name : $item->unit?->name), 'available' => ((float) $stock->quantity) . ' ' . $item->unit?->name]);
                }
            }
        }

        return $error;
    }
}
