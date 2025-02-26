<?php

namespace App\Traits;

trait HasManySyncable
{
    public function syncHasMany($items, $relation, $field = 'id', $assoc = true, $children = [], $checkin = false)
    {
        if (! empty($items)) {
            $ndIds = [];
            $records = [];
            $oItems = $this->{$relation};
            foreach ($items as $item) {
                $item_field = $assoc ? ($item[$field] ?? false) : $item;
                if ($item_field) {
                    // $nItem         = $this->$relation()->updateOrCreate([$field => $item_field], $item);
                    $exists = $this->{$relation}()->where($field, $item_field)->first();
                    if ($exists) {
                        $exists->updated_at = now();
                        $exists->fill($item)->save();
                        $nItem = $exists;
                    } else {
                        $nItem = $this->{$relation}()->create($item);
                    }
                    $nItem->update = true;
                    $records[] = $nItem;
                    $this->addChildRelations($this->id, $nItem, $children, $item, $checkin);
                    $ndIds[] = $item_field;
                } else {
                    $new = $this->{$relation}()->create($item);
                    $records[] = $new;
                    $ndIds[] = $new->{$field};
                    $this->addChildRelations($this->id, $new, $children, $item, $checkin);
                }
            }

            $oItems->filter(function ($item) use ($ndIds, $field) {
                if (! in_array($item->{$field}, $ndIds)) {
                    return $item;
                }
            })->map(function ($item) {
                return $item->forceDelete();
            });

            return $records;
        }
    }

    private static function addChildRelations($mId, $model, $children, $item, $checkin)
    {
        if (! empty($children)) {
            foreach ($children as $child) {
                if ($child['relation'] == 'serials' && $checkin) {
                    $model->createSerials($mId, $item['serials'] ?? []);
                } else {
                    if ($child['sync']) {
                        $rel = $child['relation'];
                        $model->{$rel}()->sync($item[$rel] ?? []);
                    } else {
                        $model->syncHasMany($item[$child['relation']], $child['relation'], $child['field'], $child['assoc']);
                    }
                }
            }
        }
    }
}
