<?php

namespace App\Models;

use App\Traits\ItemHelpers;

class Item extends Model
{
    use ItemHelpers;

    public $casts = [
        'variants'       => 'array',
        'has_serials'    => 'boolean',
        'has_variants'   => 'boolean',
        'track_quantity' => 'boolean',
        'track_weight'   => 'boolean',
    ];

    protected $fillable = [
        'name', 'code', 'symbology', 'track_weight', 'track_quantity', 'alert_quantity', 'rack_location', 'photo',
        'has_variants', 'variants', 'has_serials', 'sku', 'details', 'unit_id', 'account_id', 'extra_attributes',
    ];

    protected $with = ['unit:id,code,name', 'unit.subunits'];

    public function allStock()
    {
        return $this->hasMany(Stock::class);
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function checkinItems()
    {
        return $this->hasMany(CheckinItem::class);
    }

    public function checkoutItems()
    {
        return $this->hasMany(CheckoutItem::class);
    }

    public function del()
    {
        if ($this->checkinItems()->exists() || $this->checkoutItems()->exists()) {
            return false;
        }

        $this->variations->each->delete();
        $this->stockTrails->each->delete();
        $this->stock->each->delete();

        return $this->delete();
    }

    public function delP()
    {
        if ($this->checkinItems()->exists() || $this->checkoutItems()->exists()) {
            return false;
        }

        $this->categories()->detach();
        $this->stockTrails()->forceDelete();
        $this->stock()->forceDelete();
        $this->serials()->forceDelete();
        $this->variations->each(function ($variation) {
            $variation->stock()->forceDelete();
        });
        $this->variations()->forceDelete();
        log_activity(__choice('delete_text', ['record' => 'Item']), $this, $this, 'Item');

        return $this->forceDelete();
    }

    public function scopeFromCategory($query, $category)
    {
        return $query->whereHas('categories', fn ($query) => $query->where('name', 'like', '%' . $category . '%'));
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->whereHas('categories', fn ($query) => $query->where('id', $category));
    }

    public function serials()
    {
        return $this->hasMany(Serial::class)->orderBy('number');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class)->whereNull('variation_id');
    }

    public function stockTrails()
    {
        return $this->hasMany(StockTrail::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
}
