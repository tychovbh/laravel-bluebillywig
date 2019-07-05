<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig\Feature;

use Faker\Provider\Internet;
use Illuminate\Support\Arr;
use Tychovbh\Bluebillywig\Bluebillywig;
use GuzzleHttp\Exception\GuzzleException;

class LiveStreamTest extends TestCase
{
    /**
     * @var string
     */
    private $livestream_id = '';

    /**
     * LiveStreamTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->beforeApplicationDestroyed(function () {
            if ($this->livestream_id) {
                $this->deleteStream($this->bluebillywig(), $this->livestream_id);
            }
        });
    }

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
     * @return array
     */
    public function itCanCreate(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $this->assertArrayHasKey('id', $livestream);
            $this->assertArrayHasKey('state', $livestream);
            return $livestream;
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
    public function itCanIndex(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $response = $bluebillywig->retrieve('/live/stream');
            $this->assertArrayHasKey('data', $response);

            $found = false;

            foreach ($response['data'] as $data) {
                if ($data['id'] === $livestream['id']) {
                    $found = true;
                }
            }

            $this->assertTrue($found, 'Livestream not found in index!');
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @depends itCanCreate
     * @param Bluebillywig $bluebillywig
     */
    public function itCanShow(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $response = $bluebillywig->retrieve('/live/stream/' . $livestream['id']);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals($livestream['id'], Arr::get($response, 'data.id'));
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
    public function itCanResume(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $response = $bluebillywig->update('/live/stream/{id}/resume', $livestream['id']);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('available', Arr::get($response, 'data.state'));
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @depends itCanCreate
     * @param Bluebillywig $bluebillywig
     */
    public function itCanPause(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $response = $bluebillywig->update('/live/stream/{id}/pause', $livestream['id']);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('paused', Arr::get($response, 'data.state'));
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
    public function itCanRecord(Bluebillywig $bluebillywig)
    {
        $this->markTestSkipped('BBW recording doesn\'t work yet');
        try {
            $livestream = $this->createStream($bluebillywig);
            $response = $bluebillywig->update('/live/stream/{id}/recording', $livestream['id'], [
                'status' => false
            ]);
            $this->assertArrayHasKey('data', $response);
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @test
     * @depends itCanInstantiate
     * @depends itCanCreate
     * @param Bluebillywig $bluebillywig
     */
    public function itCanDelete(Bluebillywig $bluebillywig)
    {
        try {
            $livestream = $this->createStream($bluebillywig);
            $this->livestream_id = '';
            $response = $this->deleteStream($bluebillywig, $livestream['id']);
            $this->assertArrayHasKey('data', $response);
            $this->assertEquals('deleting', Arr::get($response, 'data.state'));

        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @param Bluebillywig $bluebillywig
     * @return array
     * @throws GuzzleException
     */
    private function createStream(Bluebillywig $bluebillywig)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new Internet($faker));
        $name = uniqid();
        $response = $bluebillywig->create('/live/stream', [
            'type' => 'video',
            'ingest-type' => 'rtmp',
            'ingest-variant' => 'simple',
            'stream-name' => $name
        ]);

        $this->livestream_id = $response['data']['id'];
        return $response['data'];
    }


    /**
     * @param Bluebillywig $bluebillywig
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    private function deleteStream(Bluebillywig $bluebillywig, string $id)
    {
        return $bluebillywig->delete('/live/stream', $id);
    }
}
