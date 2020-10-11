<?php

namespace Amjad\Lableb;

use Illuminate\Support\ServiceProvider;


class LablebServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/lableb.php',
            'lableb'
        );
        $this->publishes([
            __DIR__.'/config/lableb.php' => config_path('lableb.php')
        ], "lableb");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
