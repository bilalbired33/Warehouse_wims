<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AppDate implements CastsAttributes
{
    /**
     * Create a new cast class instance.
     */
    public function __construct(
        protected ?string $time = null,
    ) {}

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $date_format = get_settings('date_format');
        if ($date_format == 'php') {
            return now()->parse($value)->isoFormat($this->time ? 'lll' : 'll');
            // return now()->parse($value)->{$this->time ? 'toDayDateTimeString' : 'toFormattedDateString'}();
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return now()->parse($value);
    }
}
