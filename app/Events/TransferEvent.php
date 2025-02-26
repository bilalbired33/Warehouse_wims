<?php

namespace App\Events;

class TransferEvent
{
    public $method;

    public $original;

    public $transfer;

    public function __construct($transfer, $method = 'created', $original = null)
    {
        $this->method = $method;
        $this->original = $original;
        $this->transfer = $transfer;
    }
}
