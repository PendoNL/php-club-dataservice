<?php

namespace PendoNL\ClubDataservice\HttpClient;

interface HttpClientInterface
{
    /**
     * @param $path
     * @param $parameters
     * @return mixed
     */
    public function get($path, $parameters);
}
