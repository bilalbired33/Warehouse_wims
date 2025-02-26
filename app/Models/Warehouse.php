<?php

namespace App\Models;

class Warehouse extends Model
{
    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkin::class);
    }

    public function del()
    {
        if ($this->checkins()->exists() || $this->checkouts()->exists()) {
            return false;
        }

        $this->stock()->delete();

        return $this->delete();
    }

    public function delP()
    {
        if ($this->checkins()->exists() || $this->checkouts()->exists()) {
            return false;
        }

        log_activity(__choice('delete_text', ['record' => 'Warehouse']), $this, $this, 'Warehouse');
        $this->stock()->forceDelete();

        return $this->forceDelete();
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Stock::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function scopeOfAccount($query, $account = null)
    {
        return $query->where('account_id', $account ?? auth()->user()->account_id);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function variationStock()
    {
        return $this->hasMany(VariationStock::class);
    }
}
