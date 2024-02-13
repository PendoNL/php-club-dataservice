<?php

namespace PendoNL\ClubDataservice;

/**
 * @property-read string $naam The member name.
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?team-indeling
 */
class TeamMember extends AbstractItem
{
    const ARTICLE = 'team-indeling';

    const SHIELDED = 'Afgeschermd';
    const UNKNOWN = 'Onbekend';

    /**
     * Team member firstname.
     * 
     * @return string
     */
    public function getFirstName()
    {
        return !$this->isShielded()
            ? explode(', ', $this->naam)[1]
            : static::UNKNOWN;
    }

    /**
     * Team member lastname.
     * 
     * @return string
     */
    public function getLastName()
    {
        return !$this->isShielded()
            ? explode(', ', $this->naam)[0]
            : static::UNKNOWN;
    }

    /**
     * @return bool
     */
    public function isShielded()
    {
        return $this->naam === static::SHIELDED;
    }
}
