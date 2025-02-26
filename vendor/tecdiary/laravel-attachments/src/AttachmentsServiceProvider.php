<?php

namespace Tecdiary\Laravel\Attachments;

use Illuminate\Support\ServiceProvider;
use Tecdiary\Laravel\Attachments\Console\Commands\CleanupAttachments;
use Tecdiary\Laravel\Attachments\Console\Commands\MigrateAttachments;

class AttachmentsServiceProvider extends ServiceProvider
{
    public static $runsMigrations = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/attachments.php' => config_path('attachments.php')
        ], 'config');

        if (!class_exists('CreateAttachmentsTable')) {
            $this->publishes([
                __DIR__ . '/../migrations/2022_10_31_105524_create_attachments_table.php'
                    => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_attachments_table.php'),
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'attachments-migrations');

        if (self::shouldRunMigrations() && !class_exists('CreateAttachmentsTable')) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'attachments');

        if (config('attachments.routes.publish')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanupAttachments::class,
                MigrateAttachments::class,
            ]);
        }
    }

    /**
     * Configure Attachments to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/attachments.php', 'attachments');

        // Bind Model to Interface
        $this->app->bind(
            \Tecdiary\Laravel\Attachments\Contracts\AttachmentContract::class,
            $this->app['config']->get('attachments.attachment_model')
        );
    }

    /**
     * Determine if Attachments' migrations should be run.
     *
     * @return bool
     */
    public static function shouldRunMigrations()
    {
        return static::$runsMigrations;
    }
}
