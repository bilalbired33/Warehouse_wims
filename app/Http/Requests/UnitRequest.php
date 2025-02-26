<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'            => 'required',
            'details'         => 'nullable',
            'operation_value' => 'nullable|numeric',
            'operator'        => 'nullable|in:+,-,*,/',
            'base_unit_id'    => 'nullable|exists:units,id',
            'code'            => 'required|alpha_num|max:20|unique:units,code,' . optional($this->route('unit'))->id,
        ];
    }
}
