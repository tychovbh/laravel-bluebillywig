<?php
declare(strict_types=1);

namespace Tychovbh\Bluebillywig\Tests;

use Tychovbh\Bluebillywig\Bluebillywig;
use Orchestra\Testbench\TestCase;

class BluebillywigTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('bluebillywig.default', 'public');
        $app['config']->set('bluebillywig.publications', [
            'public' => [
                'id' => '',
                'secret' => '',
                'base_url'=> '',
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

    /**
     * @test
     */
    public function itCanInstantiate()
    {
        $bluebillywig = app('bluebillywig');
        $this->assertInstanceOf(Bluebillywig::class, $bluebillywig);
    }

    /**
     * @test
     */
    public function itCanRetrieveData()
    {
        $bluebillywig = app('bluebillywig');
        try {
            $response = $bluebillywig->retrieve('/publication');
            $this->assertEquals('active', $response['status']);
            $this->assertArrayHasKey('id', $response);
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }
}
