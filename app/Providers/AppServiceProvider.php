<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Tecdiary\Laravel\Attachments\AttachmentsServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->useLangPath(base_path('lang'));
        JsonResource::withoutWrapping();

        try {
            if (env('APP_INSTALLED') && function_exists('get_settings')) {
                try {
                    $settings = get_settings(['timezone', 'default_locale']);
                    $settings['timezone'] ??= config('app.timezone');
                    config(['app.timezone' => $settings['timezone']]);
                    Carbon::setLocale($settings['default_locale'] ?? 'en');
                } catch (\Exception $e) {
                    logger()->error('Provider settings error: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function register()
    {
        $this->app->extend(
            \Illuminate\Translation\Translator::class,
            fn ($translator) => new \App\Helpers\Translator($translator->getLoader(), $translator->getLocale())
        );

        AttachmentsServiceProvider::ignoreMigrations();
        $this->app->extend(\Illuminate\Pagination\LengthAwarePaginator::class, fn ($paginator) => $paginator->onEachSide(2));
    }
}
