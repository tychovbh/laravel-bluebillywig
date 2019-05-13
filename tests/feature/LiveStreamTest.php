<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig\Feature;

use Tychovbh\Bluebillywig\Bluebillywig;
use GuzzleHttp\Exception\GuzzleException;

class LiveStreamTest extends TestCase
{
    /**
     * @test
     * @return Bluebillywig
     */
    public function itCanInstantiate(): Bluebillywig
    {
        return $this->bluebillywig();
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param Bluebillywig $bluebillywig
     */
    public function itCanIndex(Bluebillywig $bluebillywig)
    {
        try {
            $response = $bluebillywig->retrieve('/live/stream');
            $this->assertArrayHasKey('data', $response);

            var_dump($response);
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param Bluebillywig $bluebillywig
     * @return array
     */
    public function itCanCreate(Bluebillywig $bluebillywig)
    {
        try {
            $faker = \Faker\Factory::create();
            $name = $faker->name;
            $response = $bluebillywig->create('/live/stream', [
                'type' => 'video',
                'ingest-type' => 'rtmp',
                'ingest-variant' => 'simple',
                'stream-name' => $name
            ]);

            $this->assertArrayHasKey('data', $response);
            $stream = $response['data'];
            $this->assertArrayHasKey('id', $stream);
            $this->assertArrayHasKey('status', $stream);

            return $response;
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @param Bluebillywig $bluebillywig
     */
    public function itCanShow(Bluebillywig $bluebillywig)
    {
        try {
            $response = $bluebillywig->retrieve('/live/stream/' . '18032ccb-2ded-4d3b-aed4-8ae72d4d9807');
            $this->assertArrayHasKey('data', $response);
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }
}
