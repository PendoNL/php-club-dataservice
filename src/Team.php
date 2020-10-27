<?php

namespace PendoNL\ClubDataservice;

class Team extends AbstractItem
{
    /** @var array $competitions */
    private $competitions = [];

    /** @var TeamMember[]|array $teamMembers */
    private $teamMembers = [];

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

    /**
     * Get all team members.
     * 
     * @return TeamMember[]|array
     */
    public function getMembers()
    {
        if (count($this->teamMembers) != 0) {
            return $this->teamMembers;
        }

        $membersData = $this->api->request('team-indeling', [
            'teamcode' => $this->teamcode,
            'lokaleteamcode' => $this->lokaleteamcode,
        ]);

        foreach ($membersData as $memberData) {
            $this->teamMembers[] = new TeamMember($this->api, $memberData);
        }

        return $this->teamMembers;
    }
}
