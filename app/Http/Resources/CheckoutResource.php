<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'           => $this->id,
            'date'         => $this->date,
            'draft'        => $this->draft,
            'details'      => $this->details,
            'reference'    => $this->reference,
            'user_id'      => $this->user_id,
            'contact_id'   => $this->contact_id,
            'warehouse_id' => $this->warehouse_id,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'deleted_at'   => $this->deleted_at,
            'user'         => $this->whenLoaded('user'),
            'items'        => $this->whenLoaded('items'),
            'contact'      => $this->whenLoaded('contact'),
            'warehouse'    => $this->whenLoaded('warehouse'),
            'attachments'  => $this->whenLoaded('attachments'),
        ];
    }
}
