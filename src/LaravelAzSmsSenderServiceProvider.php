<?php

namespace Orkhanahmadov\LaravelAzSmsSender;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelAzSmsSenderServiceProvider
 * @package Orkhanahmadov\LaravelAzSmsSender
 */
class LaravelAzSmsSenderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config' => config_path('az-sms-sender'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/Migration');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/main.php', 'az-sms-sender-main'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/config/providers.php', 'az-sms-sender-providers'
        );

        $this->app->bind('laravelazsmssender', function () {
            return new \Orkhanahmadov\LaravelAzSmsSender\Sender;
        });
    }
}
