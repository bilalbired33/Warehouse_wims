<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\LogActivity;
use App\Traits\Paginatable;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends Eloquent
{
    use HasFactory;
    use LogActivity;
    use Paginatable;
    use SoftDeletes;

    public $casts = ['extra_attributes' => 'array'];

    public static $hasReference = false;

    public static $hasUser = false;

    protected $guarded = [];

    protected static $logAttributesToIgnore = ['account_id'];

    protected static $logOnlyDirty = true;

    protected $setHash = false;

    protected $setUser = false;

    protected static $submitEmptyLogs = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }

    public function getExtraAttributesAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'extra_attributes');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($this->getRouteKeyName(), $value)->withTrashed()->first();
    }

    public function scopeOfAccount($query, $account = null)
    {
        return $query->where('account_id', $account ?: optional(auth()->user())->account_id);
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('extra_attributes');
    }

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return now()->parse($date)->toDayDateTimeString();
    // }

    protected static function booted()
    {
        static::addGlobalScope('account', function (Builder $builder) {
            $builder->where('account_id', getAccountId(1));
        });

        if (static::$hasUser) {
            static::addGlobalScope('mine', function (Builder $builder) {
                $user = auth()->user();
                if (! $user->hasRole('Super Admin') && ! $user->view_all) {
                    $builder->where('user_id', $user->id);
                }
            });
        }

        static::creating(function ($model) {
            if (! $model->account_id) {
                $model->account_id = getAccountId();
            }
            if ($model->setHash && ! $model->hash) {
                $model->hash = uuid4();
            }
            if ($model->setUser && ! $model->user_id) {
                $model->user_id = auth()->user()->id;
            }
            if ($model::$hasReference && ! $model->reference) {
                $model->reference = get_reference($model);
            }
        });
    }
}
