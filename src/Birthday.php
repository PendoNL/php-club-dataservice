<?php

namespace PendoNL\ClubDataservice;

/**
 * @property-read string $verjaardag    The member birthday (format: 1 Jan).
 * @property-read string $volledigenaam The member full name.
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?verjaardagen
 */
class Birthday extends AbstractItem
{
    const ARTICLE = 'verjaardagen';

    const SHIELDED = 'Afgeschermd';
    const UNKNOWN = 'Onbekend';

    /**
     * The member firstname.
     *
     * @return string
     */
    public function getFirstName()
    {
        return !$this->isShielded()
            ? explode(' ', $this->volledigenaam, 2)[0]
            : static::UNKNOWN;
    }

    /**
     * The member lastname.
     *
     * @return string
     */
    public function getLastName()
    {
        return !$this->isShielded()
            ? explode(' ', $this->volledigenaam, 2)[1]
            : static::UNKNOWN;
    }

    /**
     * Get member full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->volledigenaam;
    }

    /**
     * Get a date time object for birthday value.
     *
     * @return \DateTimeInterface
     */
    public function getDate()
    {
        return \DateTime::createFromFormat(
            'j M H:i:s', $this->verjaardag . ' 00:00:00'
        );
    }

    /**
     * @return bool
     */
    public function isShielded()
    {
        return $this->volledigenaam === static::SHIELDED;
    }
}
