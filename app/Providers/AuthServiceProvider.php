<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\Checkin'    => 'App\Policies\EditPolicy',
        'App\Models\Checkout'   => 'App\Policies\EditPolicy',
        'App\Models\Transfer'   => 'App\Policies\EditPolicy',
        'App\Models\Adjustment' => 'App\Policies\EditPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}
