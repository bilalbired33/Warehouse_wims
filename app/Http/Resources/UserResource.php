<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'username'           => $this->username,
            'view_all'           => $this->view_all,
            'edit_all'           => $this->edit_all,
            'deleted_at'         => $this->deleted_at,
            'warehouse_id'       => $this->warehouse_id,
            'photo'              => $this->profile_photo_url,
            'two_factor_enabled' => (bool) $this->two_factor_secret,
            'roles'              => $this->roles()->get(['name'])->pluck('name'),
        ];
    }
}
