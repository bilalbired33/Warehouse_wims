<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowStockAlert extends Mailable implements ShouldQueue
{
    use Queueable;

    public $data;

    public $settings;

    public $single;

    public $user;

    public function __construct($data, $single = false, $user = null)
    {
        $this->data = $data;
        $this->user = $user;
        $this->single = $single;
        $this->settings = json_decode(get_settings());
    }

    public function build()
    {
        return $this->subject(__('Low Stock Alert'))->view('mail.stock');
    }
}
