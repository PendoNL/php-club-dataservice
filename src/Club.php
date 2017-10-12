<?php

namespace PendoNL\ClubDataservice;

class Club extends AbstractItem
{
    public $teams = [];
    public $afgelastingen = [];

    /**
     * Return all teams (and map competitions).
     *
     * @param array $arguments
     * @return array
     */
    public function getTeams($arguments = [])
    {
        if ($this->teams) {
            return $this->teams;
        }

        $response = $this->api->request('teams', $arguments);

        // First loop the teams
        foreach($response as $item) {
            $team = new Team($this->api, $item);

            if(!array_key_exists($team->teamcode, $this->teams)) {
                $this->teams[$team->teamcode] = $team;
            }
        }

        // Then loop the competitions
        foreach($response as $item) {
            $competition = new Competition($this->api, $item);
            $this->competitions[$competition->poulecode] = $competition;
        }

        return  $this->teams;
    }

    /**
     * Return all competitions.
     *
     * @return array
     */
    public function getCompetitions()
    {
        if(count($this->teams) == 0) {
            $this->getTeams();
        }

        return $this->competitions;
    }

    /**
     * Return a single team.
     *
     * @param  string $code
     * @return Team|null
     */
    public function getTeam($code)
    {
        if(count($this->teams) == 0) {
            $this->getTeams();
        }

        if (isset($this->teams[$code])) {
            return $this->teams[$code];
        }

        return null;
    }

    /**
     * Return a single competition.
     *
     * @param  string $code
     * @return Team|null
     */
    public function getCompetition($code)
    {
        $competitions = $this->getCompetitions();

        if (isset($competitions[$code])) {
            return $competitions[$code];
        }

        return null;
    }

    /**
     * Afgelastingen.
     *
     * @param array $arguments
     * @return array
     */
    public function afgelastingen($arguments = [])
    {
        if ($this->afgelastingen) {
            return $this->afgelastingen;
        }

        $response = $this->api->request('afgelastingen', $arguments);

        foreach($response as $item){
            $afgelasting = new Match($this->api, $item);
            $this->afgelastingen[] = $afgelasting;
        }

        return  $this->afgelastingen;
    }

}
