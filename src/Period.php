<?php

namespace PendoNL\ClubDataservice;

class Period extends AbstractItem
{
    public $waarde;
    public $omschrijving;
    public $huidig;

    /**
     * @param Api $api
     */
    public function __construct($api)
    {
        $this->api = $api;
    }

    /**
     * Get period number.
     *
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->waarde;
    }
}
