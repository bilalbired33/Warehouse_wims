<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LocaleLength implements Rule
{
    public function message()
    {
        return __('The locale length should be 2 or 5 characters e.g. en or en-US.');
    }

    public function passes($attribute, $value)
    {
        return strlen($value) == 2 || strlen($value) == 5;
    }
}
