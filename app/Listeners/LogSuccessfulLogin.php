<?php

namespace App\Listeners;

class LogSuccessfulLogin
{
    public function handle($event)
    {
        log_activity(__choice('action_text', ['record' => 'User (' . $event->user->username . ')', 'action' => 'logged in']), $event->user, $event->user, 'User');
    }
}
