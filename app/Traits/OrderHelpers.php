<?php

namespace App\Traits;

trait OrderHelpers
{
    public static function scopeActive($query)
    {
        return $query->whereNull('draft')->orWhere('draft', 0);
    }

    public static function scopeDraft($query)
    {
        return $query->where('draft', 1);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->{$t . 'Trashed'}())
            ->when($filters['draft'] ?? null, fn ($q, $t) => $t == 'yes' ? $q->draft() : $q->active())
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->search($search));
    }

    public static function scopeOfContact($query, $contact)
    {
        return $query->where('contact_id', $contact);
    }

    public function scopeReportFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->withTrashed())
            ->when($filters['draft'] ?? null, fn ($q) => $q->draft())
            ->when($filters['reference'] ?? null, fn ($q, $r) => $q->where('reference', $r))
            ->when($filters['end_date'] ?? null, fn ($q, $d) => $q->where('date', '<=', $d))
            ->when($filters['user_id'] ?? null, fn ($q, $rId) => $q->where('user_id', $rId))
            ->when($filters['start_date'] ?? null, fn ($q, $d) => $q->where('date', '>=', $d))
            ->when($filters['contact_id'] ?? null, fn ($q, $rId) => $q->where('contact_id', $rId))
            ->when($filters['warehouse_id'] ?? null, fn ($q, $rId) => $q->where('warehouse_id', $rId))
            ->when($filters['to_warehouse_id'] ?? null, fn ($q, $rId) => $q->where('to_warehouse_id', $rId))
            ->when($filters['from_warehouse_id'] ?? null, fn ($q, $rId) => $q->where('from_warehouse_id', $rId))
            ->when($filters['end_created_at'] ?? null, fn ($q, $d) => $q->where('created_at', '<=', $d))
            ->when($filters['start_created_at'] ?? null, fn ($q, $d) => $q->where('created_at', '>=', $d))
            ->when($filters['category_id'] ?? null, fn ($q, $rId) => $q->whereHas('items', fn ($query) => $query->ofCategory($rId)));
    }

    public function scopeSearch($query, $s)
    {
        $query->where(fn ($q) => $q->where('id', 'like', "%{$s}%")->orWhere('reference', 'like', "%{$s}%"));
    }
}
