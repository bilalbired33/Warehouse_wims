<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'code'            => $this->code,
            'details'         => $this->details,
            'operator'        => $this->operator,
            'deleted_at'      => $this->deleted_at,
            'base_unit_id'    => $this->base_unit_id,
            'operation_value' => $this->operation_value,
            'base_unit'       => optional($this->baseUnit)->only(['id', 'code', 'name']),
        ];
    }
}
