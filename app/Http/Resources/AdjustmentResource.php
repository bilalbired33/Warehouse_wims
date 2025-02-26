<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'           => $this->id,
            'date'         => $this->date,
            'type'         => $this->type,
            'draft'        => $this->draft,
            'details'      => $this->details,
            'user_id'      => $this->user_id,
            'reference'    => $this->reference,
            'warehouse_id' => $this->warehouse_id,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'deleted_at'   => $this->deleted_at,
            'user'         => $this->whenLoaded('user'),
            'items'        => $this->whenLoaded('items'),
            'warehouse'    => $this->whenLoaded('warehouse'),
            'attachments'  => $this->whenLoaded('attachments'),
        ];
    }
}
