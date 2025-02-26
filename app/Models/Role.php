<?php

namespace App\Models;

use App\Traits\LogActivity;
use App\Traits\Paginatable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseRole
{
    use HasFactory;
    use LogActivity;
    use Paginatable;
    use SoftDeletes;

    // protected $hidden = ['pivot', 'permissions'];

    public function del()
    {
        if ($this->users()->exists() || $this->name == 'Super Admin') {
            return false;
        }

        return $this->delete();
    }

    public function delP()
    {
        log_activity(__choice('delete_text', ['record' => 'Role']), $this, $this, 'Role');
        $this->users()->detach();

        return $this->forceDelete();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function scopeOfAccount($query)
    {
        return $query->where('account_id', auth()->user()->account_id);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (! $model->account_id) {
                $model->account_id = auth()->user()->account_id;
            }
        });
    }
}
