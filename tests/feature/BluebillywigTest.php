<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Bluebillywig\Feature;

use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Bluebillywig\Bluebillywig;
use Tychovbh\Tests\Bluebillywig\TestCase;

class BluebillywigTest extends TestCase
{
    /**
     * @test
     * @return Bluebillywig
     */
    public function itCanInstantiate(): Bluebillywig
    {
        $bluebillywig = app('bluebillywig');
        $this->assertInstanceOf(Bluebillywig::class, $bluebillywig);

        return $bluebillywig;
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
    public function itCanCreate(Bluebillywig $bluebillywig) : array
    {
        try {
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

            $this->assertArrayHasKey('id', $response);
            $this->assertArrayHasKey('title', $response);
            $this->assertEquals($name, $response['title']);

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
     * @depends itCanCreate
     * @param Bluebillywig $bluebillywig
     * @param array $video
     */
    public function itCanUpdate(Bluebillywig $bluebillywig, array $video)
    {
        try {
            $faker = \Faker\Factory::create();
            $name = $faker->name;
            $response = $bluebillywig->update((int)$video['id'], '/sapi/mediaclip', [
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
     * @depends itCanCreate
     * @param Bluebillywig $bluebillywig
     * @param array $video
     */
    public function itCanDelete(Bluebillywig $bluebillywig, array $video)
    {
        try {
            $response = $bluebillywig->delete((int)$video['id'], '/sapi/mediaclip');

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
}
