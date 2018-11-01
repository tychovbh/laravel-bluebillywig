<?php

namespace Tychovbh\Bluebillywig;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class BluebillywigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = __DIR__ . '/../config/bluebillywig.php';
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('bluebillywig.php')], 'laravel-bluebillywig');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('bluebillywig');
        }
        $this->mergeConfigFrom($source, 'bluebillywig');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('bluebillywig', function (Container $app) {
            return new Bluebillywig($this->app->make('GuzzleHttp\Client'), $app['config']);
        });
    }
}
