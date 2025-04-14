<?php

namespace GoldPrice\Http;

use GuzzleHttp\Client;
use GoldPrice\Contracts\HttpClientInterface;

/**
 * Class GuzzleHttpClient
 *
 * An HTTP client implementation that uses Guzzle to perform GET requests.
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * GuzzleHttpClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $url): string
    {
        $response = $this->client->get($url);
        return $response->getBody()->getContents();
    }
}
