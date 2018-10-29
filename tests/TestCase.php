<?php

declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        $app['config']->set('bluebillywig.default', 'public');
        $app['config']->set('bluebillywig.publications', [
            'public' => [
                'id' => env('ID'),
                'secret' => env('SECRET'),
                'base_url'=> env('BASE_URL'),
            ]
        ]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\Tychovbh\Bluebillywig\BluebillywigServiceProvider::class];
    }
}
