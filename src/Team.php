<?php

namespace PendoNL\ClubDataservice;

class Team extends AbstractItem
{
    /** @var array $competitions */
    private $competitions = [];

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
