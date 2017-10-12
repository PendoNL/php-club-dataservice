<?php

namespace PendoNL\ClubDataservice;

class Team extends AbstractItem
{
    public $teamcode;
    public $teamnaam;
    public $lokaleteamcode;
    public $geslacht;
    public $teamsoort;
    public $leeftijdscategorie;
    public $speeldagteam;

    /** @var array $competitions */
    private $competitions = [];

    /**
     * @param Api $api
     */
    public function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * All competitions where this team is active in.
     *
     * @return array
     */
    public function getCompetitions()
    {
        if (count($this->competitions) != 0) {
            return $this->competitions;
        }

        foreach($this->api->getClub()->getCompetitions() as $competition) {
            if($competition->teamcode == $this->teamcode) {
                $this->competitions[] = $competition;
            }
        }

        return  $this->competitions;
    }
}
