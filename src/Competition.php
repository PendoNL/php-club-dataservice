<?php

namespace PendoNL\ClubDataservice;

class Competition extends AbstractItem
{
    public $poulecode;
    public $teamcode;
    public $competitienaam;
    public $klasse;
    public $poule;
    public $klassepoule;
    public $spelsoort;
    public $competitiesoort;
    public $kalespelsoort;
    public $speeldag;

    private $results = [];
    private $fixtures = [];
    private $table = [];

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Get the team from the parent.
     *
     * @return mixed
     */
    public function getTeam()
    {
        return $this->api->getClub()->getTeam($this->teamcode);
    }

    /**
     * Match fixtures for this competition
     *
     * @param array $arguments
     * @return array
     */
    public function fixtures($arguments = [])
    {
        if ($this->fixtures) {
            return $this->fixtures;
        }

        $response = $this->api->request('poule-programma', array_merge([
            'poulecode' => $this->poulecode,
        ], $arguments));

        foreach($response as $item) {
            $fixture = $this->api->map($item, new Match($this->api));
            $this->fixtures[$fixture->wedstrijdcode] = $fixture;
        }

        return  $this->fixtures;
    }

    /**
     * Results for this competition
     *
     * @param array $arguments
     * @return array
     */
    public function results($arguments = [])
    {
        if ($this->results) {
            return $this->results;
        }

        $response = $this->api->request('pouleuitslagen', array_merge([
            'poulecode' => $this->poulecode,
        ], $arguments));

        foreach($response as $item) {
            $result = $this->api->map($item, new Match($this->api));
            $this->results[$result->wedstrijdcode] = $result;
        }

        return  $this->results;
    }

    /**
     * Poule stand
     *
     * @param array $arguments
     * @return array
     */
    public function table($arguments = [])
    {
        if ($this->table) {
            return $this->table;
        }

        $response = $this->api->request('poulestand', array_merge([
            'poulecode' => $this->poulecode,
        ], $arguments));

        foreach($response as $item) {
            var_dump($item);exit;
            $tablePosition = $this->api->map($item, new TablePosition($this->api));
            $this->table[$item['positie']] = $tablePosition;
        }

        ksort($this->table);

        return $this->table;
    }

    /**
     * Get a specific (set of) positions in the table
     * @param array $positions
     * @return bool|mixed
     */
    public function getPosition($positions)
    {
        $table = $this->table();

        if(!is_array($positions)) {
            $positions = [$positions];
        }

        $return = [];

        foreach($positions as $position) {
            if(array_key_exists($position, $table)) {
                $return[] = $table[$position];
            }
        }

        return $return;
    }

}
