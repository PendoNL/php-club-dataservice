<?php

namespace PendoNL\ClubDataservice;

class Match extends AbstractItem
{
    /** @var Match */
    public $wedstrijdcode;
    public $wedstrijdnummer;
    public $wedstrijddatum;
    public $datum;
    public $datumopgemaakt;
    public $teamnaam;
    public $thuisteamid;
    public $thuisteam;
    public $thuisteamclubrelatiecode;
    public $uitteamid;
    public $uitteam;
    public $uitteamclubrelatiecode;
    public $eigenteam;
    public $accommodatie;
    public $plaats;
    public $teamvolgorde;
    public $rjders;
    public $scheidsrechters;
    public $veld;
    public $zaalofveld;
    public $vertrektijd;
    public $aanvangstijd;
    public $status;
    public $wedstrijd;
    public $uitslag;
    public $link;

    /** @var array $matches */
    private $matches = [];

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Get a single match.
     *
     * @param $id
     * @return array|mixed
     */
    public function getMatch($id)
    {
        if (array_key_exists($id, $this->matches)) {
            return $this->matches[$id];
        }

        $response = $this->api->request('wedstrijd-informatie', [
            'wedstrijdcode' => $id,
        ]);

        foreach($response as $item) {
            $matchInfo = $this->api->map($item, new MatchInfo($this->api));
            $this->matches[$id] = $matchInfo;
        }

        return  $this->matches[$id];
    }
}
