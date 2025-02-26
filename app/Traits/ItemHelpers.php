<?php

namespace App\Traits;

use App\Models\Warehouse;

trait ItemHelpers
{
    public function addVariations()
    {
        if ($this->has_variants && ! empty($this->variants)) {
            $warehouses = Warehouse::all();
            $meta = $this->getCombinations(collect($this->variants)->pluck('option', 'name')->all());
            foreach ($meta as $data) {
                if (config('database.default') == 'sqlite') {
                    $variation = null;
                    foreach ($this->variations as $v) {
                        if (json_encode($v->meta) == json_encode($data)) {
                            $variation = $v;
                        }
                    }
                } else {
                    $variation = $this->variations()->where('account_id', getAccountId())->whereJsonContains('meta', $data)->exists();
                }
                if (! $variation) {
                    $variation = $this->variations()->create([
                        'meta'       => $data,
                        'sku'        => uuid1(),
                        'account_id' => getAccountId(),
                    ]);
                    if ($variation) {
                        foreach ($warehouses as $warehouse) {
                            $variation->stock()->create([
                                'weight'        => 0,
                                'quantity'      => 0,
                                'item_id'       => $this->id,
                                'warehouse_id'  => $warehouse->id,
                                'rack_location' => $this->rack_location,
                                'account_id'    => getAccountId(),
                            ]);
                        }
                    }
                }
            }
        }

        return $this;
    }

    public function saveRelations($data)
    {
        $categories = [];
        $variations = $data['variations'] ?? [];
        if (isset($data['category_id'])) {
            $categories[] = $data['category_id'];
        }
        if (isset($data['child_category_id'])) {
            $categories[] = $data['child_category_id'];
        }
        $this->setStock();
        $this->setRack($data);
        $this->setVariations($variations);
        $this->categories()->sync($categories);
        $this->refresh()->syncVariations();

        return $this;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, fn ($q, $t) => $q->{$t . 'Trashed'}())
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->search($search))
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->ofCategory($category));
    }

    public function scopeSearch($query, $s)
    {
        $query->where(
            fn ($q) => $q->where('code', 'like', "%{$s}%")->orWhere('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%")
                ->orWhereHas('categories', fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"))
        );
    }

    public function setRack($data)
    {
        if (! empty($data['warehouses'])) {
            foreach ($data['warehouses'] as $wh) {
                $this->stock()->where(['warehouse_id' => $wh['warehouse_id']])
                    ->update(['rack_location' => $wh['rack'] ?? $data['rack_location'] ?? null]);
            }
        }
    }

    public function setStock()
    {
        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {
            foreach ($warehouses as $warehouse) {
                $stock = $this->stock()->where(['warehouse_id' => $warehouse->id])->first();
                if (! $stock) {
                    $this->stock()->create([
                        'weight'         => 0,
                        'quantity'       => 0,
                        'warehouse_id'   => $warehouse->id,
                        'rack_location'  => $this->rack_location,
                        'alert_quantity' => $this->alert_quantity,
                    ]);
                }
                // $this->stock()->updateOrCreate([
                //     'account_id'   => getAccountId(),
                //     'warehouse_id' => $warehouse->id,
                // ], [
                //     'weight'         => 0,
                //     'quantity'       => 0,
                //     'rack_location'  => $this->rack_location,
                //     'alert_quantity' => $this->alert_quantity,
                // ]);
            }
        }
    }

    public function setVariations(array $variations)
    {
        if (! empty($variations)) {
            foreach ($variations as $variation) {
                if (isset($variation['name']) && ! empty($variation['option'])) {
                    $instance = $this->variations()->updateOrCreate(['name' => $variation['name']], $variation);
                    if (! empty($variation['stock'])) {
                        foreach ($variation['stock'] as $stock) {
                            $instance->stock()->updateOrCreate(['warehouse_id' => $stock['warehouse_id']], $stock);
                        }
                    }
                }
            }
        }
    }

    private function getCombinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    if ($property_value) {
                        $tmp[] = array_merge($result_item, [$property => $property_value]);
                    }
                }
            }
            $result = $tmp;
        }

        return $result;
    }

    private function syncVariations()
    {
        $current = [];
        $variations = [];
        $meta = $this->getCombinations(collect($this->variants)->pluck('option', 'name')->all());
        foreach ($meta as $data) {
            if (config('database.default') == 'sqlite') {
                $variation = null;
                foreach ($this->variations as $v) {
                    if (json_encode($v->meta) == json_encode($data)) {
                        $variation = $v;
                        $variations[] = $variation;
                        $current[] = $variation->id;
                    }
                }
            } else {
                $variation = $this->variations()->where('account_id', getAccountId())->whereJsonContains('meta', $data)->first();
                if ($variation) {
                    $variation->update(['meta' => $data]);
                    $variations[] = $variation;
                    $current[] = $variation->id;
                }
            }
        }

        if (! empty($current)) {
            $missingVariations = $this->variations()->whereNotIn('id', $current)->get();
            foreach ($missingVariations as $missingVariation) {
                $this->stockTrails()->where('variation_id', $missingVariation->id)->forceDelete();
                $missingVariation->stock()->forceDelete();
                $missingVariation->forceDelete();
            }

            $warehouses = Warehouse::all();
            foreach ($warehouses as $warehouse) {
                $quantity = 0;
                foreach ($variations as $v) {
                    $quantity += $v->stock()->ofWarehouse($warehouse->id)->first()?->quantity ?? 0;
                }
                $this->stock()->ofWarehouse($warehouse->id)->update(['quantity' => $quantity]);
            }
        }

        if (! $this->has_variants && $this->variations()->exists()) {
            $this->variations->each(function ($variation) {
                $variation->stock()->forceDelete();
            });
            $this->variations()->forceDelete();
        }
    }
}
