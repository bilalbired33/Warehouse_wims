<?php

namespace App\Listeners;

class LogOtherDeviceLogout
{
    public function handle($event)
    {
        log_activity(__choice('action_text', ['record' => 'User (' . $event->user->username . ')', 'action' => 'logged out of other devices']), $event->user, $event->user, 'User');
    }
}
