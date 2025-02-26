<?php

namespace App\Models;

class Variation extends Model
{
    public $casts = ['meta' => 'array'];

    protected $fillable = ['sku', 'item_id', 'rack_location', 'account_id', 'meta'];

    public function getUnitAttribute()
    {
        return $this->pivot->unit_id ? Unit::find($this->pivot->unit_id) : null;
    }

    // protected static function booted()
    // {
    //     static::deleted(function ($variation) {
    //         $variation->stock()->delete();
    //     });
    //     static::forceDeleted(function ($variation) {
    //         $variation->stock()->forceDelete();
    //     });
    // }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeOfItem($query, $item)
    {
        return $query->where('item_id', $item);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouseStock($warehouse = null)
    {
        return $this->stock()->ofWarehouse($warehouse);
    }
}
