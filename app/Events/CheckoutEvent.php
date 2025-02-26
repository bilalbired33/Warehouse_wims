<?php

namespace App\Events;

class CheckoutEvent
{
    public $checkout;

    public $method;

    public $original;

    public function __construct($checkout, $method = 'created', $original = null)
    {
        $this->method = $method;
        $this->checkout = $checkout;
        $this->original = $original;
    }
}
