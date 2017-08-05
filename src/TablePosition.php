<?php

namespace PendoNL\ClubDataservice;

class TablePosition extends AbstractItem
{
    public $positie;
    public $teamnaam;
    public $clubrelatiecode;
    public $gespeeldewedstrijden;
    public $gewonnen;
    public $gelijk;
    public $verloren;
    public $doelpuntenvoor;
    public $doelpuntentegen;
    public $doelsaldo;
    public $verliespunten;
    public $punten;
    public $eigenteam;

    /**
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }
}
