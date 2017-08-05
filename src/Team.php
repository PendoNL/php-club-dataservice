<?php

namespace PendoNL\ClubDataservice;

class Team extends AbstractItem
{
    public $teamcode;
    public $teamnaam;
    public $lokaleteamcode;

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
     * All competitions where this team is active in
     *
     * @return array
     */
    public function getCompetitions()
    {
        if ($this->competitions) {
            return $this->competitions;
        }

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

        return  $this->competitions;
    }
}
