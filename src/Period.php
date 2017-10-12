<?php

namespace PendoNL\ClubDataservice;

class Period extends AbstractItem
{
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
