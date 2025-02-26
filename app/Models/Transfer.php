<?php

namespace App\Models;

use App\Casts\AppDate;
use App\Traits\OrderHelpers;
use App\Traits\HasAttachments;
use App\Traits\HasManySyncable;

class Transfer extends Model
{
    use HasAttachments;
    use HasManySyncable;
    use OrderHelpers;

    public static $hasReference = true;

    public static $hasUser = true;

    protected $fillable = [
        'date', 'reference',  'draft', 'hash', 'to_warehouse_id', 'from_warehouse_id',
        'user_id', 'approved_by', 'account_id', 'details', 'extra_attributes', 'approved_at',
    ];

    protected $setHash = true;

    protected $setUser = true;

    protected function casts(): array
    {
        return [
            'extra_attributes' => 'array',
            'date'             => AppDate::class,
            'created_at'       => AppDate::class . ':time',
            'updated_at'       => AppDate::class . ':time',
        ];
    }

    public function del()
    {
        $this->items->each->delete();

        return $this->delete();
    }

    public function delP()
    {
        log_activity(__choice('delete_text', ['record' => 'Item']), $this, $this, 'Item');
        $this->items->each->forceDelete();

        return $this->forceDelete();
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(TransferItem::class)->orderBy('id')->withTrashed();
    }

    public function scopeSearch($query, $s)
    {
        $query->where(
            fn ($q) => $q->where('id', 'like', "%{$s}%")->orWhere('reference', 'like', "%{$s}%")
                ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('username', 'like', "%{$s}%"))
                ->orWhereHas('toWarehouse', fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"))
                ->orWhereHas('fromWarehouse', fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"))
        );
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
