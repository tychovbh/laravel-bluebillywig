<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig\Feature;

use Tychovbh\Bluebillywig\Bluebillywig;
use Tychovbh\Tests\Bluebillywig\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    /**
     * Get Bluebillywig instance
     * @return Bluebillywig
     */
    public function bluebillywig()
    {
        $bluebillywig = app('bluebillywig');
        $this->assertInstanceOf(Bluebillywig::class, $bluebillywig);

        return $bluebillywig;
    }
}
