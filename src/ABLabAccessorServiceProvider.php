<?php

namespace ABLab\Accessor;

use ABLab\Accessor\ABLabAccessor;
use Illuminate\Support\ServiceProvider;

class ABLabAccessorServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ab-lab-cache', function () {
            return new ABLabCaching(config('ab-lab-accessor.cache'), config('ab-lab-accessor.cache-config'));
        });

        $this->app->singleton('ab-lab-accessor', function () {
            return new ABLabAccessor($this->app->make('ab-lab-cache'), config('ab-lab-accessor'));
        });
    }

    public function provides()
    {
        return [
            'ab-lab-accessor',
            'ab-lab-cache',
        ];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ab-lab-accessor.php' => config_path('ab-lab-accessor.php'),
        ], 'ab-lab-accessor');
    }
}