<?php

namespace App\Mail;

use App\Models\Adjustment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailAdjustment extends Mailable implements ShouldQueue
{
    use Queueable;

    public $adjustment;

    public $mail;

    public $settings;

    public function __construct(Adjustment $adjustment, $preview = false)
    {
        $this->mail = ! $preview;
        $this->adjustment = $adjustment;
        $this->settings = json_decode(get_settings());
    }

    public function build()
    {
        return $this->subject(__('Adjustment Details'))->view('mail.adjustment');
    }
}
