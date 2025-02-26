<?php

namespace App\Mail;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTransfer extends Mailable implements ShouldQueue
{
    use Queueable;

    public $mail;

    public $settings;

    public $transfer;

    public function __construct(Transfer $transfer, $preview = false)
    {
        $this->mail = ! $preview;
        $this->transfer = $transfer;
        $this->settings = json_decode(get_settings());
    }

    public function build()
    {
        return $this->subject(__('Transfer Details'))->view('mail.transfer');
    }
}
