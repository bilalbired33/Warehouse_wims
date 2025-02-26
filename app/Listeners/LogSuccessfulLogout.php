<?php

namespace App\Listeners;

class LogSuccessfulLogout
{
    public function handle($event)
    {
        log_activity(__choice('action_text', ['record' => 'User (' . $event->user->username . ')', 'action' => 'logged out']), $event->user, $event->user, 'User');
    }
}
