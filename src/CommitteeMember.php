<?php

namespace PendoNL\ClubDataservice;

/**
 * @property-read string      $lid           The member name.
 * @property-read string      $rolid         Id for the member role.
 * @property-read string      $rol           The role name.
 * @property-read string|null $email         The member e-mail address.
 * @property-read string|null $email2        The member secondary e-mail address.
 * @property-read string|null $telefoon      The member phone number.
 * @property-read string|null $telefoon2     The member secondary phone number.
 * @property-read string|null $mobiel        The member cellphone number.
 * @property-read string      $startdatum    The member initial start date.
 * @property-read string|null $startdatetime The member initial start date (with time).
 * @property-read string      $einddatum     The member initial due date.
 * @property-read string|null $einddatumtime The member initial due date (with time).
 * @property-read string      $informatie    Member information.
 * @property-read string|null $adres         The member address information.
 * @property-read string|null $plaats        The member residence.
 * @property-read string|null $foto          The member photo (binary format).
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?commissie-leden
 */
class CommitteeMember extends AbstractItem
{
    const ARTICLE = 'commissie-leden';

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
            ? explode(', ', $this->lid)[1]
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
            ? explode(', ', $this->lid)[0]
            : static::UNKNOWN;
    }

    /**
     * @return bool
     */
    public function isShielded()
    {
        return $this->lid === static::SHIELDED;
    }

    /**
     * Get team member photo.
     *
     * @return string|null
     */
    public function getImageTag()
    {
        if (!$this->lid) {
            return null;
        }

        $alt = $this->lid;
        $src = 'data:image/jpeg;base64,' . $this->foto;

        return '<img alt="' . $alt . '" src="' . $src . '" />';
    }
}
