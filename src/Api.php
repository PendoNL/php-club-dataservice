<?php

namespace PendoNL\ClubDataservice;

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
    private $api_key = '';

    /**
     * ClubDataservice constructor.
     *
     * @param $api_key
     * @param HttpClientInterface $client
     */
    public function __construct($api_key, HttpClientInterface $client = null)
    {
        $this->api_key = $api_key;
        $this->client = $client ?: new HttpClient();
        $this->mapper = new JsonMapper();
    }

    /**
     * Make a request to the API
     *
     * @param $path
     * @param array $parameters
     * @return mixed
     * @throws InvalidResponseException
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
     */
    private function buildQueryData($parameters)
    {
        return http_build_query(array_merge(['client_id' => $this->api_key], $parameters));
    }

    /**
     * Map data to object
     *
     * @param $json
     * @param $object
     * @return object
     * @throws \JsonMapper_Exception
     */
    public function map($json, $object)
    {
        return $this->mapper->map((object) $json, $object);
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
            $data['bezoekadres'] = (object)$response['bezoekadres'];
        }

        $this->club = $this->map($data, new Club($this));

        return $this->club;
    }
}
