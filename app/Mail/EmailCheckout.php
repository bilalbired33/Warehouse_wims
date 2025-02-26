<?php

namespace App\Mail;

use App\Models\Checkout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCheckout extends Mailable implements ShouldQueue
{
    use Queueable;

    public $checkout;

    public $mail;

    public $settings;

    public function __construct(Checkout $checkout, $preview = false)
    {
        $this->mail = ! $preview;
        $this->checkout = $checkout;
        $this->settings = json_decode(get_settings());
    }

    public function build()
    {
        return $this->subject(__('Checkout Details'))->view('mail.checkout');
    }
}
