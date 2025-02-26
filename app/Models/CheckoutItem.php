<?php

namespace App\Models;

use App\Traits\HasManySyncable;

class CheckoutItem extends Model
{
    use HasManySyncable;

    protected $fillable = [
        'checkout_id', 'item_id', 'weight', 'quantity', 'unit_id', 'batch_no', 'expiry_date', 'account_id', 'draft', 'warehouse_id',
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class)->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->whereHas('item', fn ($query) => $query->ofCategory($category));
    }

    public function serials()
    {
        return $this->belongsToMany(Serial::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class, 'item_id', 'item_id');
    }

    public function stockTrails()
    {
        return $this->morphMany(StockTrail::class, 'subject');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function variations()
    {
        // return $this->hasMany(Variation::class);
        return $this->belongsToMany(Variation::class)->withPivot('weight', 'quantity', 'unit_id');
    }
}
