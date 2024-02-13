<?php

namespace PendoNL\ClubDataservice;

use PendoNL\ClubDataservice\Exception\InvalidResponseException;

/**
 * @property-read int|string $lokaleteamcode     The local team code.
 * @property-read int        $poulecode          The poul code.
 * @property-read string     $teamnaam           The name of the team.
 * @property-read string     $compeitienaam      The team competition name.
 * @property-read string     $klasse             The team order.
 * @property-read string     $poule              The team poule.
 * @property-read string     $klassepoule        The team poule order.
 * @property-read string     $spelsoort          The team game/day.
 * @property-read string     $competitiesoort    Competition type.
 * @property-read string     $geslacht           The team gender.
 * @property-read string     $teamsoort          Type of team.
 * @property-read string     $leeftijdscategorie Team age category.
 * @property-read string     $kalespelsoort      Team "kalespel" type.
 * @property-read string     $speeldag           Team day of game.
 * @property-read string     $speeldagteam       $speeldag + "speeldag".
 * @property-read string     $more               API more link.
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?teams
 */
class Team extends AbstractItem
{
    /** @var int|null */
    public $teamcode;

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
     *
     * @throws InvalidResponseException
     */
    public function getMembers()
    {
        if (count($this->teamMembers) != 0) {
            return $this->teamMembers;
        }

        $membersData = $this->api->request(TeamMember::ARTICLE, [
            'teamcode' => $this->teamcode,
            'lokaleteamcode' => $this->lokaleteamcode,
        ]);

        foreach ($membersData as $memberData) {
            $this->teamMembers[] = new TeamMember($this->api, $memberData);
        }

        return $this->teamMembers;
    }
}
