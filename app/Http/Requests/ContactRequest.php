<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'details' => 'nullable',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|required_without:email',
            'name'    => 'required|max:50|unique:contacts,name,' . optional($this->route('contact'))->id,
        ];
    }
}
