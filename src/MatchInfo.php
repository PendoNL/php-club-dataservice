<?php

namespace PendoNL\ClubDataservice;

class MatchInfo extends AbstractItem
{
    /** @var Match */
    public $wedstrijdinformatie;
    public $wedstrijdnummer;
    public $veldnaam;
    public $veldlocatie;
    public $vertrektijd;
    public $rijder;
    public $thuisscore;
    public $uitscore;
    public $klasse;
    public $wedstrijdtype;
    public $competitietype;
    public $categorie;
    public $wedstrijddatetime;
    public $wedstrijddatum;
    public $wedstrijddatumopgemaakt;
    public $aanvangsttijd;
    public $aanvangsttijdopgemaakt;
    public $duur;
    public $speltype;
    public $aanduiding;
    public $poulecode;
    public $poule;
    public $thuisteamid;
    public $thuisteam;
    public $uitteamid;
    public $uitteam;
    public $opmerkingen;

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }
}
