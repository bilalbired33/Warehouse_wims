<?php

namespace App\Models;

use Carbon\Carbon;
use App\Casts\AppDate;

class StockTrail extends Model
{
    protected $fillable = [
        'item_id', 'warehouse_id', 'quantity', 'type', 'memo', 'weight', 'variation_id', 'unit_id',
    ];

    protected $with = ['item:id,code,name', 'warehouse:id,code,name', 'unit:id,code,name', 'variation:id,meta'];

    protected function casts(): array
    {
        return [
            'created_at' => AppDate::class . ':time',
            'updated_at' => AppDate::class . ':time',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function referencesObject($object)
    {
        $this->subject_id = $object->id;
        $this->subject_type = get_class($object);
        $this->save();

        return $this;
    }

    public function scopeBefore($query, Carbon $date)
    {
        return $query->where('created_at', '<=', $date);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfWarehouse($query, $warehouse)
    {
        return $query->where('warehouse_id', $warehouse);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
