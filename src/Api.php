<?php

namespace PendoNL\ClubDataservice;

use http\Exception\InvalidArgumentException;
use JsonMapper;
use PendoNL\ClubDataservice\HttpClient\HttpClient;
use PendoNL\ClubDataservice\HttpClient\HttpClientInterface;
use PendoNL\ClubDataservice\Exception\InvalidResponseException;

class Api
{
    /** @var HttpClient  */
    protected $client;

    /** @var JsonMapper  */
    protected $mapper;

    /** @var Club $club */
    protected $club;

    /**
     * The ClientID needed to communicate with the API
     *
     * @var string
     */
    protected $api_key = '';

    /**
     * ClubDataservice constructor.
     *
     * @param $api_key
     * @param HttpClientInterface $client
     */
    public function __construct($api_key = '', HttpClientInterface $client = null)
    {
        $this->api_key = $api_key;
        $this->client = $client ?: new HttpClient();
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Make a request to the API
     *
     * @param $path
     * @param array $parameters
     * @return mixed
     * @throws InvalidResponseException|\InvalidArgumentException
     */
    public function request($path, $parameters = [])
    {
        try {
            $response = $this->client->get($path, $this->buildQueryData($parameters));
        }catch(\GuzzleHttp\Exception\ParseException $e){
            throw new InvalidResponseException("Cannot parse message: ".$e->getResponse()->getBody(), $e->getCode());
        }catch(\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();
            throw new InvalidResponseException("Cannot finish request: " . $response->getReasonPhrase(). ', Request:' . $e->getRequest()->getUri(), $response->getStatusCode());
        }catch(\Exception $e){
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * Builds the query string with the client ID attached
     *
     * @param $parameters
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function buildQueryData($parameters)
    {
        if (empty($this->api_key)) {
            throw new InvalidArgumentException("No 'api_key' set");
        }

        return http_build_query(array_merge(['client_id' => $this->api_key], $parameters));
    }

    /**
     * Get the club data
     *
     * @return Club
     */
    public function getClub()
    {
        if($this->club) {
            return $this->club;
        }

        $response = $this->request('clubgegevens');

        $data = $response['gegevens'];
        if (isset($response['bezoekadres'])) {
            $data['bezoekadres'] = new Bezoekadres($this, $response['bezoekadres']);
        }

        $this->club = new Club($this, $data);

        return $this->club;
    }
}
