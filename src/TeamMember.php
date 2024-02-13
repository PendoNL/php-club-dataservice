<?php

namespace PendoNL\ClubDataservice;

/**
 * @property-read string|null $relatiecode The member relation code.
 * @property-read string      $naam        The member name.
 * @property-read string|null $geslacht    The member gender.
 * @property-read string      $rol         The member role.
 * @property-read string      $functie     The member function.
 * @property-read string|null $einddatum   The member end date.
 * @property-read string|null $email       The member e-mail address.
 * @property-read string|null $email2      The member secondary e-mail address.
 * @property-read string|null $telefoon    The member phone number.
 * @property-read string|null $telefoon2   The member secondary phone number.
 * @property-read string|null $mobiel      The member cellphone number.
 * @property-read string|null $foto        The member photo (binary format).
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

    /**
     * Get team member photo.
     *
     * @return string|null
     */
    public function getImageTag()
    {
        if (!$this->naam) {
            return null;
        }

        $alt = $this->naam;
        $src = 'data:image/jpeg;base64,' . $this->foto;

        return '<img alt="' . $alt . '" src="' . $src . '" />';
    }
}
