<?php

namespace App\Models;

class Stock extends Model
{
    protected $fillable = ['account_id', 'item_id', 'variation_id', 'warehouse_id', 'quantity', 'rack_location', 'alert_quantity', 'weight'];

    protected $with = ['variation', 'warehouse:id,code,name'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeMain($query)
    {
        return $query->whereNull('variation_id');
    }

    public function scopeOfItem($query, $item)
    {
        return $query->where('item_id', $item);
    }

    public function scopeOfWarehouse($query, $warehouse)
    {
        return $query->where('warehouse_id', $warehouse);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
