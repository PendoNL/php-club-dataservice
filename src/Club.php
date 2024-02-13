<?php

namespace PendoNL\ClubDataservice;

use PendoNL\ClubDataservice\Exception\InvalidResponseException;

/**
 * Club class.
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/
 */
class Club extends AbstractItem
{
    /** @var Team[]|array  */
    public $teams = [];

    /** @var array{id: int, info: TeamInfo} */
    public $teamInfo = [];

    public $afgelastingen = [];

    /** @var Committee[]|array  */
    public $committees = [];

    /** @var Birthday[]|array */
    public $birthdays = [];

    /**
     * Return all teams (and map competitions).
     *
     * @param array $arguments
     *
     * @return Team[]|array
     *
     * @throws InvalidResponseException
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
     * Return a single team.
     *
     * @param string $code
     *
     * @return Team|null
     *
     * @throws InvalidResponseException
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
     * Get team info.
     *
     * @param int $teamCode      The team code.
     * @param int $localTeamCode The local team code.
     *
     * @return TeamInfo|null
     */
    public function getTeamInfo($teamCode, $localTeamCode = -1)
    {
        // Set cache key.
        $key = $teamCode . '-' . $localTeamCode;

        if (isset($this->teamInfo[$key])) {
            return $this->teamInfo[$key];
        }

        try {
            $response = $this->api->request(TeamInfo::ARTICLE, [
                'teamcode' => $teamCode,
                'lokaleteamcode' => $localTeamCode,
            ]);

            $this->teamInfo[$key] = new TeamInfo($this->api, $response['team']);

            return $this->teamInfo[$key];
        } catch (InvalidResponseException $ex) {
            return null;
        }
    }

    /**
     * Return all competitions.
     *
     * @return Competition[]|array
     *
     * @throws InvalidResponseException
     */
    public function getCompetitions()
    {
        if(count($this->teams) == 0) {
            $this->getTeams();
        }

        return $this->competitions;
    }

    /**
     * Return a single competition.
     *
     * @param string $code
     *
     * @return Team|null
     *
     * @throws InvalidResponseException
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
     *
     * @return array
     *
     * @throws InvalidResponseException
     */
    public function afgelastingen($arguments = [])
    {
        if ($this->afgelastingen) {
            return $this->afgelastingen;
        }

        $response = $this->api->request('afgelastingen', $arguments);

        foreach($response as $item){
            $this->afgelastingen[] = new Game($this->api, $item);
        }

        return  $this->afgelastingen;
    }

    /**
     * Return all committees.
     *
     * @param array $arguments
     *
     * @return Committee[]|array
     *
     * @throws InvalidResponseException
     */
    public function getCommittees($arguments = [])
    {
        if ($this->committees) {
            return $this->committees;
        }

        $response = $this->api->request(Committee::ARTICLE, $arguments);

        // Loop committees.
        foreach ($response as $item) {
            $committee = new Committee($this->api, $item);

            if(!array_key_exists($committee->commissiecode, $this->committees)) {
                $this->committees[$committee->commissiecode] = $committee;
            }
        }

        return  $this->committees;
    }

    /**
     * Get Committee.
     *
     * @param int $commissiecode The unique commission code.
     *
     * @return Committee|null
     *
     * @throws InvalidResponseException
     */
    public function getCommittee($commissiecode)
    {
        $committees = $this->getCommittees();

        if (isset($committees[$commissiecode])) {
            return $committees[$commissiecode];
        }

        return null;
    }

    /**
     * Get birthdays.
     *
     * @return Birthday[]|array
     */
    public function getBirthdays($arguments = [])
    {
        if ($this->birthdays) {
            return $this->birthdays;
        }

        $response = $this->api->request(Birthday::ARTICLE, $arguments);

        // Loop birthdays.
        foreach ($response as $item) {
            $this->birthdays[] = new Birthday($this->api, $item);
        }

        return $this->birthdays;
    }
}
