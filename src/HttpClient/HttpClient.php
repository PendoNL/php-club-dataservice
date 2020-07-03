<?php

namespace PendoNL\ClubDataservice\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * Default options for the GuzzleClient
     *
     * @var array
     */
    protected $options = [
        'base_uri' => 'https://data.sportlink.com/',
        'verify' => 'false',
        'defaults' => [
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'pendo-club-dataservice-api'
            ]
        ]
    ];

    /**
     * Construct a new HttpClient instance. Optional parameters can be supplied.
     * A Guzzle client can optionally be passed as argument, but a new instance
     * will be created by default.
     *
     * @param $options
     * @param GuzzleClientInterface $client
     */
    public function __construct($options = [], GuzzleClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $this->client = $client ?: new GuzzleClient($this->options);
    }

    /**
     * @param $path
     * @param $parameters
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function get($path, $parameters)
    {
        $response = $this->client->request('GET', $path . '?' . $parameters);
        return json_decode($response->getBody()->getContents(), true);
    }
}
