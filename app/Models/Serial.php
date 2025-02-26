<?php

namespace App\Models;

class Serial extends Model
{
    protected $fillable = [
        'number', 'sold', 'item_id', 'account_id', 'warehouse_id',
        'check_in_id', 'check_in_item_id', 'check_out_id', 'check_out_item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('sold')->orWhere('sold', 0);
    }

    public function scopeInitial($query)
    {
        return $query->whereNULL('check_in_id');
    }

    public function scopeOfWarehouse($query, $warehouse)
    {
        return $query->where('warehouse_id', $warehouse);
    }

    public function scopeSold($query)
    {
        return $query->where('sold', 1);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
