<?php

namespace App\Traits;

trait Paginatable
{
    public function getPerPage()
    {
        return get_settings('per_page') ?? 15;
    }
}
