<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AttachmentEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $attachments;

    public $model;

    public function __construct($model, $attachments)
    {
        $this->model = $model;
        $this->attachments = $attachments;
    }
}
