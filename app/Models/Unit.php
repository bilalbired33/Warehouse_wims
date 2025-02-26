<?php

namespace App\Models;

class Unit extends Model
{
    protected $with = ['baseUnit'];

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function del()
    {
        if ($this->subunits()->exists() || $this->items()->exists()) {
            return false;
        }
        $this->subunits->each->delete();

        return $this->delete();
    }

    public function delP()
    {
        if ($this->subunits()->exists() || $this->items()->exists()) {
            return false;
        }
        log_activity(__choice('delete_text', ['record' => 'Unit']), $this, $this, 'Unit');
        $this->subunits->each->forceDelete();

        return $this->forceDelete();
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function scopeBase($query)
    {
        return $query->whereNULL('base_unit_id');
    }

    public static function scopeChildrenOf($query, $base_unit_id)
    {
        return $query->where('base_unit_id', $base_unit_id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->{$t . 'Trashed'}())
            ->when($filters['base_unit'] ?? null, fn ($q, $base) => $q->childrenOf($base))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->search($search));
    }

    public function scopeSearch($query, $s)
    {
        $query->where(fn ($q) => $q->where('code', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%"));
    }

    public function subunits()
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }
}
