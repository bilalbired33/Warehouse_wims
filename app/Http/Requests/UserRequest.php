<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone'        => 'nullable',
            'roles'        => 'array|min:1',
            'name'         => 'required|max:50',
            'edit_all'     => 'nullable|boolean',
            'view_all'     => 'nullable|boolean',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'username'     => 'required|unique:users,username,' . optional($this->route('user'))->id,
            'email'        => 'required|email|unique:users,email,' . optional($this->route('user'))->id,
            'password'     => optional($this->route('user'))->id ? 'nullable|confirmed|min:8' : 'required|min:8',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();

        if ($this->input('password')) {
            $data['password'] = Hash::make($this->input('password'));
        } else {
            unset($data['password']);
        }

        // if ($this->file('photo')) {
        //     $data['photo_path'] = $this->file('photo')->store('users');
        // }

        return $data;
    }
}
