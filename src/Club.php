<?php

namespace PendoNL\ClubDataservice;

class Club extends AbstractItem
{
    /** @vars logo */
    public $logo;
    public $kleinlogo;

    /** @vars club */
    public $clubcode;
    public $clubnaam;
    public $informatie;
    public $oprichtingsdatum;
    public $oprichtingsdatetime;
    public $naamsecretaris;
    public $kvknummer;

    /** @vars contact */
    public $telefoonnummer;
    public $fax;
    public $email;
    public $website;
    public $straatnaam;
    public $huisnummer;
    public $huisnummertoevoeging;
    public $postcode;
    public $plaats;

    /** @var Bezoekadres */
    public $bezoekadres;

    /** @vars bank */
    public $banknummer;
    public $tennamevan;
    public $tennamevanplaats;

    /** @vars thuistenue */
    public $thuisshirtkleur;
    public $thuisbroekkleur;
    public $thuissokkenkleur;

    /** @vars uittenue */
    public $uitshirtkleur;
    public $uitbroekkleur;
    public $uitsokkenkleur;

    /** @var array $teams */
    private $teams = [];
    private $competitions = [];
    private $afgelastingen = [];

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

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
            $team = $this->api->map($item, new Team($this->api));

            if(!array_key_exists($team->teamcode, $this->teams)) {
                $this->teams[$team->teamcode] = $team;
            }
        }

        // Then loop the competitions
        foreach($response as $item) {
            $competition = $this->api->map($item, new Competition($this->api));
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
        $teams = $this->getTeams();

        if (isset($teams[$code])) {
            return $teams[$code];
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
            $afgelasting = $this->api->map($item, new Match($this->api));
            $this->afgelastingen[] = $afgelasting;
        }

        return  $this->afgelastingen;
    }

}
