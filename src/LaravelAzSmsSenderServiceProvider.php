<?php

namespace Orkhanahmadov\LaravelAzSmsSender;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelAzSmsSenderServiceProvider.
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
            __DIR__.'/config' => config_path('az-sms-sender'),
        ]);

        $this->loadMigrationsFrom(__DIR__.'/Migration');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/main.php', 'az-sms-sender-main'
        );

        $this->mergeConfigFrom(
            __DIR__.'/Config/providers.php', 'az-sms-sender-providers'
        );

        $this->app->bind('laravelazsmssender', function () {
            return new \Orkhanahmadov\LaravelAzSmsSender\Sender();
        });
    }
}
