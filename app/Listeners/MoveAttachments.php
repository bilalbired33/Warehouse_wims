<?php

namespace App\Listeners;

use App\Events\AttachmentEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoveAttachments implements ShouldQueue
{
    use InteractsWithQueue;

    public function failed(AttachmentEvent $event, $exception)
    {
        Log::error('MoveAttachments failed!', ['Error' => $exception, 'model' => $event->model]);
    }

    public function handle(AttachmentEvent $event)
    {
        $event->model->moveAttachments($event->attachments);
    }
}
