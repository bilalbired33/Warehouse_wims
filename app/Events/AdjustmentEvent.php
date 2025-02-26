<?php

namespace App\Events;

class AdjustmentEvent
{
    public $adjustment;

    public $method;

    public $original;

    public function __construct($adjustment, $method = 'created', $original = null)
    {
        $this->method = $method;
        $this->original = $original;
        $this->adjustment = $adjustment;
    }
}
