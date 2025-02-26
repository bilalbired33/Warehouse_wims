<?php

namespace App\Events;

class CheckinEvent
{
    public $checkin;

    public $method;

    public $original;

    public function __construct($checkin, $method = 'created', $original = null)
    {
        $this->method = $method;
        $this->checkin = $checkin;
        $this->original = $original;
    }
}
