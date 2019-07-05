<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig\Feature;

use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Bluebillywig\Bluebillywig;

class MediaclipTest extends TestCase
{
    /**
     * @var string
     */
    protected $mediaclip_id = '';

    /**
     * BluebillywigTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->beforeApplicationDestroyed(function () {
            if ($this->mediaclip_id) {
                $this->deleteMediaclip($this->bluebillywig(), $this->mediaclip_id);
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
     */
    public function itCanRetrieveData(Bluebillywig $bluebillywig)
    {
        try {
            $response = $bluebillywig->retrieve('/sapi/publication');
            $this->assertEquals('active', $response['status']);
            $this->assertArrayHasKey('id', $response);
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
            $mediaclip = $this->createMediaclip($bluebillywig);
            $response = $bluebillywig->retrieve('/sapi/mediaclip/' . $mediaclip['id']);
            $this->assertArrayHasKey('id', $response);
            $this->assertEquals($mediaclip['id'], $response['id']);
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
    public function itCanRetrieveWithParams(Bluebillywig $bluebillywig)
    {
        try {
            $response = $bluebillywig->retrieve('/sapi/mediaclip', [
                'limit' => 2,
            ]);

            $this->assertArrayHasKey('items', $response);
            $this->assertArrayHasKey('numfound', $response);
            $this->assertLessThanOrEqual(2, count($response['items']));
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
    public function itCanCreate(Bluebillywig $bluebillywig): array
    {
        try {
            $mediaclip = $this->createMediaclip($bluebillywig);
            $this->assertArrayHasKey('id', $mediaclip);
            $this->assertArrayHasKey('title', $mediaclip);
            return $mediaclip;
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
    public function itCanUpdate(Bluebillywig $bluebillywig)
    {
        try {
            $mediaclip = $this->createMediaclip($bluebillywig);
            $faker = \Faker\Factory::create();
            $name = $faker->name;
            $response = $bluebillywig->update('/sapi/mediaclip/{id}', $mediaclip['id'], [
                'title' => $name,
            ]);

            $this->assertArrayHasKey('id', $response);
            $this->assertArrayHasKey('title', $response);
            $this->assertEquals($name, $response['title']);
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
    public function itCanDelete(Bluebillywig $bluebillywig)
    {
        try {
            $mediaclip = $this->createMediaclip($bluebillywig);
            $response = $this->deleteMediaclip($bluebillywig, $mediaclip['id']);
            $this->mediaclip_id = '';
            $this->assertArrayHasKey('code', $response);
            $this->assertEquals('200', $response['code']);
            $this->assertArrayHasKey('body', $response);
            $this->assertEquals('mediaclip was succesfully removed', $response['body']);
        } catch (\Exception $exception) {
            $this->assertTrue(false, $exception->getMessage());
        } catch (GuzzleException $exception) {
            $this->assertTrue(false, $exception->getMessage());
        }
    }

    /**
     * @param Bluebillywig $bluebillywig
     * @return mixed
     * @throws GuzzleException
     */
    private function createMediaclip(Bluebillywig $bluebillywig)
    {
        $faker = \Faker\Factory::create();
        $name = $faker->name;
        $response = $bluebillywig->create('/sapi/mediaclip', [
            'title' => $name,
            'originalfilename' => $name . '.mov',
            'sourceid' => $faker->Uuid,
            'description' => $faker->text,
            'author' => $faker->name,
            'status' => 'published'
        ]);

        $this->mediaclip_id = $response['id'];
        return $response;
    }

    /**
     * @param Bluebillywig $bluebillywig
     * @param string $id
     * @return array
     * @throws GuzzleException
     */
    private function deleteMediaclip(Bluebillywig $bluebillywig, string $id)
    {
        return $bluebillywig->delete('/sapi/mediaclip', $id);
    }
}
