<?php

namespace App\Models;

class Category extends Model
{
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->child()->with('children');
    }

    public function del()
    {
        if ($this->child()->exists() || $this->items()->exists()) {
            return false;
        }

        $this->child->each->delete();

        return $this->delete();
    }

    public function delP()
    {
        if ($this->child()->exists() || $this->items()->exists()) {
            return false;
        }

        log_activity(__choice('delete_text', ['record' => 'Category']), $this, $this, 'Category');
        $this->child->each->forceDelete();

        return $this->forceDelete();
    }

    public function expenses()
    {
        return $this->morphedByMany(Expense::class, 'categorizable');
    }

    public function items()
    {
        return $this->morphedByMany(Item::class, 'categorizable');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public static function scopeChildrenOf($query, $parent_id)
    {
        return $query->where('parent_id', $parent_id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->{$t . 'Trashed'}())
            ->when($filters['parent'] ?? null, fn ($q, $parent) => $q->childrenOf($parent))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->search($search));
    }

    public static function scopeOnlyParents($query)
    {
        return $query->whereNULL('parent_id');
    }

    public function scopeSearch($query, $s)
    {
        $query->where(fn ($q) => $q->where('code', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%"));
    }
}
