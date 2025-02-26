<?php

namespace App\Models;

use App\Casts\AppDate;
use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class Activity extends ActivityModel
{
    use Paginatable;

    protected function casts(): array
    {
        return [
            'created_at' => AppDate::class . ':time',
            'updated_at' => AppDate::class . ':time',
        ];
    }

    public function scopeCurrentAccount($query)
    {
        return $query->where('account_id', auth()->user()->account_id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('log_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHasMorph('causer', '*', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('username', 'like', '%' . $search . '%');
                    });
            });
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('account', function (Builder $builder) {
            $builder->where('account_id', auth()->user()->account_id);
        });

        static::creating(function ($activity) {
            if (! $activity->account_id) {
                $activity->account_id = auth()->user() ? auth()->user()->account_id : $activity->subject->account_id;
            }
        });
    }
}
