<?php

namespace App\Listeners;

class LogPasswordReset
{
    public function handle($event)
    {
        log_activity(__choice('action_text', ['record' => 'User  (' . $event->user->username . ') password', 'action' => 'reset']), $event->user, $event->user, 'User');
    }
}
