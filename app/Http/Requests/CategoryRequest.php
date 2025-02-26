<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => 'required',
            'parent_id' => 'nullable|exists:categories,id',
            'code'      => 'required|alpha_dash|unique:categories,code,' . optional($this->route('category'))->id,
        ];
    }
}
