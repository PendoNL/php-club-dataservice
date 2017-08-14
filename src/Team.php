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

        /**
         * According to Sportlink, this also gets inactive poules
         * Therefor we filter the main competition array on
         * the Club entity.
         *
        $response = $this->api->request('teampoulelijst', [
            'teamcode' => $this->teamcode,
            'lokaleteamcode' => $this->lokaleteamcode,
        ]);

        foreach($response as $item) {
            preg_match_all("/\((.*)\)/i", $item['teamnaam'], $matches);
            $item['poulenaam'] = $matches[1][0];

            $competition = $this->api->map($item, new Competition($this->api));
            $this->competitions[$competition->poulecode] = $competition;
        }
         */

        foreach($this->api->getClub()->getCompetitions() as $competition) {
            if($competition->teamcode == $this->teamcode) {
                $this->competitions[] = $competition;
            }
        }

        return  $this->competitions;
    }
}
