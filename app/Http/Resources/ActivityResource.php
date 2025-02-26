<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'log_name'     => $this->log_name,
            'description'  => $this->description,
            'properties'   => $this->properties,
            'subject_id'   => $this->subject_id,
            'subject_type' => $this->subject_type,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'causer'       => optional($this->causer)->only(['id', 'name', 'username']),
        ];
    }
}
