<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'active'  => 'nullable',
            'address' => 'nullable',
            'phone'   => 'nullable',
            'email'   => 'nullable|email',
            'name'    => 'required|max:50',
            'logo'    => 'nullable|image|max:500',
            'code'    => 'required|max:20|unique:warehouses,code,' . optional($this->route('warehouse'))->id,
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        if ($this->has('logo') && $this->logo) {
            $path = $this->logo->store('/images', 'logos');
            $data['logo'] = Storage::disk('logos')->url($path);
        } else {
            unset($data['logo']);
        }

        return $data;
    }
}
