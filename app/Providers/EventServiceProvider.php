<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\AttachmentEvent::class => [\App\Listeners\MoveAttachments::class],
        \App\Events\CheckinEvent::class    => [\App\Listeners\CheckinEventListener::class],
        \App\Events\CheckoutEvent::class   => [\App\Listeners\CheckoutEventListener::class],
        \App\Events\TransferEvent::class   => [\App\Listeners\TransferEventListener::class],
        \App\Events\AdjustmentEvent::class => [\App\Listeners\AdjustmentEventListener::class],

        \Illuminate\Auth\Events\PasswordReset::class     => [\App\Listeners\LogPasswordReset::class],
        \Illuminate\Auth\Events\Login::class             => [\App\Listeners\LogSuccessfulLogin::class],
        \Illuminate\Auth\Events\Logout::class            => [\App\Listeners\LogSuccessfulLogout::class],
        \Illuminate\Auth\Events\OtherDeviceLogout::class => [\App\Listeners\LogOtherDeviceLogout::class],
        \Illuminate\Auth\Events\Registered::class        => [\Illuminate\Auth\Listeners\SendEmailVerificationNotification::class],
    ];

    public function boot()
    {
    }
}
