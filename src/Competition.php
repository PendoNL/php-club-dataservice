<?php

namespace PendoNL\ClubDataservice;

class Competition extends AbstractItem
{
    private $results = [];
    private $fixtures = [];
    private $table = [];
    private $periodTables = [];
    private $periods = [];

    /**
     * Get the team from the parent.
     *
     * @return mixed
     */
    public function getTeam()
    {
        $team = $this->api->getClub()->getTeam($this->teamcode);

        return $team;
    }

    /**
     * Get the periods by a competition
     *
     * @param array $arguments
     * @return array
     */
    public function periods($arguments = [])
    {
        if ($this->periods) {
            return $this->periods;
        }

        // Add -1 period (this is the full table)
        $periode = new Period($this->api, ['waarde' => -1, 'omschrijving' => -1, 'huidig' => 'nee']);
        $this->periods[$periode->waarde] = $periode;

        $response = $this->api->request('keuzelijst-periodenummers', array_merge([
            'poulecode' => $this->poulecode,
        ], $arguments));

        foreach($response as $item) {
            $periode = new Period($this->api, $item);
            $this->periods[$periode->waarde] = $periode;
        }

        return  $this->periods;
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
            'aantaldagen' => 365,
        ], $arguments));

        foreach($response as $item) {
            $match = new Match($this->api, $item);
            $this->fixtures[$match->wedstrijdcode] = $match;
        }

        return $this->fixtures;
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

        if (!is_null($this->poulecode)) {
            $response = $this->api->request('poulestand', array_merge([
                'poulecode' => $this->poulecode,
            ], $arguments));

            foreach($response as $item) {
                $tablePosition = new TablePosition($this->api, $item);
                $this->table[$item['positie']] = $tablePosition;
            }

            ksort($this->table);

            return $this->table;
        }

        return [];
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
            $result = new Match($this->api, $item);
            $this->results[$result->wedstrijdcode] = $result;
        }

        return  $this->results;
    }

    /**
     * Get period tables.
     *
     * @param array $arguments
     * @return array
     */
    public function periodTables($arguments = [])
    {
        if ($this->periodTables) {
            return $this->periodTables;
        }

        foreach($this->periods() as $period) {
            $response = $this->api->request('periodestand', array_merge([
                'poulecode' => $this->poulecode,
                'periodenummer' => $period->waarde,
            ], $arguments));

            foreach($response as $item) {
                $tablePosition = new TablePosition($this->api, $item);
                $this->periodTables[$period->waarde][$item['positie']] = $tablePosition;
            }

            ksort($this->periodTables[$period->waarde]);
        }

        return $this->periodTables;
    }

    /**
     * Return period table.
     *
     * @param $period
     * @return mixed|null
     */
    public function getPeriod($period)
    {
        if(count($this->periodTables) == 0) {
            $this->periodTables();
        }

        if(array_key_exists($period, $this->periodTables)) {
            return $this->periodTables[$period];
        }

        return null;
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
