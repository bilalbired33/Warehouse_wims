<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'sku'              => $this->sku,
            'code'             => $this->code,
            'name'             => $this->name,
            'symbology'        => $this->symbology,
            'alert_quantity'   => $this->alert_quantity,
            'rack_location'    => $this->rack_location,
            'variants'         => $this->variants,
            'details'          => $this->details,
            'unit_id'          => $this->unit_id,
            'account_id'       => $this->account_id,
            'deleted_at'       => $this->deleted_at,
            'extra_attributes' => $this->extra_attributes,
            'has_serials'      => $this->has_serials == 1 ? true : false,
            'has_variants'     => $this->has_variants == 1 ? true : false,
            'track_weight'     => $this->track_weight == 1 ? true : false,
            'track_quantity'   => $this->track_quantity == 1 ? true : false,
            'photo'            => $this->photo ? Storage::disk('assets')->url($this->photo) : null,
            'user'             => $this->whenLoaded('user'),
            'unit'             => $this->whenLoaded('unit'),
            'stock'            => $this->whenLoaded('stock'),
            'serials'          => $this->whenLoaded('serials'),
            'all_stock'        => $this->whenLoaded('allStock'),
            'categories'       => $this->whenLoaded('categories'),
            'variations'       => $this->whenLoaded('variations'),
        ];
    }
}
