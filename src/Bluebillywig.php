<?php
declare(strict_types=1);

namespace Tychovbh\Bluebillywig;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Bluebillywig\Exceptions\ConfigurationException;
use Illuminate\Contracts\Config\Repository;

require_once 'hotp-php/hotp.php';

/**
 * @property Client client
 */
class Bluebillywig
{
    /**
     * @var array
     */
    private $publication;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $base_url;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var array
     */
    private $config;

    /**
     * Bluebillywig constructor.
     * @param Client $client
     * @param Repository $config
     * @throws ConfigurationException
     */
    public function __construct(Client $client, Repository $config)
    {
        $this->config = $config->get('bluebillywig');
        $this->publication = $this->config('default');
        $this->client = $client;

        return $this->publication($this->publication);
    }

    /**
     * Get publication from config
     * @param string $key
     * @return string|int
     * @throws ConfigurationException
     */
    private function config(string $key)
    {
        $value = array_get($this->config, $key);

        if (!$value) {
            throw new ConfigurationException(sprintf(
                'Key {%s} missing in bluebillywig.php configuration file!',
                $key
            ));
        }

        return $value;
    }

    /**
     * Get publication token by secret
     * @param string $secret
     * @return string
     */
    private function token(string $secret): string
    {
        return \HOTP::generateByTime($secret, 120)->toHOTP(10);
    }

    /**
     * Perform Bluebillywig request
     * @param string $endpoint
     * @param string $method
     * @param array $params
     * @return array
     * @throws \Exception
     * @throws GuzzleException
     */
    private function request(string $endpoint, string $method, array $params = []): array
    {
        $request = sprintf('%s%s', $this->base_url, $endpoint);
        $res = $this->client->request($method, $request, array_merge($params, [
            'headers' => [
                'rpctoken' => $this->id . '-' . $this->token($this->secret),
            ]
        ]));
        return json_decode($res->getBody()->getContents(), true) ?? [];
    }

    /**
     * Request data from Bluebillywig
     * @param string $endpoint
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function retrieve(string $endpoint, array $params = []): array
    {
        return $this->request($endpoint, 'GET', ['query' => $params]);
    }

    /**
     * Post data to Bluebillywig
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $endpoint, array $params = []): array
    {
        return $this->request($endpoint, 'POST', ['json' => $params]);
    }

    /**
     * Update data Bluebillywig
     * @param int $id
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws \Exception
     * @throws GuzzleException
     */
    public function update(int $id, string $endpoint, array $params = []): array
    {
        return $this->request($endpoint . '/' . $id, 'PUT', ['json' => $params]);
    }

    /**
     * Delete data Bluebillywig
     * @param int $id
     * @param string $endpoint
     * @return array
     * @throws \Exception
     * @throws GuzzleException
     */
    public function delete(int $id, string $endpoint): array
    {
        return $this->request($endpoint . '/' . $id, 'DELETE');
    }

    /**
     * Set Bluebillywig Publication
     * @param string $publication
     * @return Bluebillywig
     * @throws ConfigurationException
     */
    public function publication(string $publication): Bluebillywig
    {
        $this->publication = $publication;
        $this->id = $this->config(sprintf('publications.%s.id', $publication));
        $this->base_url = $this->config(sprintf('publications.%s.base_url', $publication));
        $this->secret = $this->config(sprintf('publications.%s.secret', $publication));

        return $this;
    }
}
