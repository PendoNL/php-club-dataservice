<?php

namespace PendoNL\ClubDataservice;

/**
 * @property-read string      $teamnaam      The name of the team.
 * @property-read string|null $begindatum    Begin date of team.
 * @property-read string|null $begindatetime Begin date of team (with time).
 * @property-read string|null $einddatum     End date of team.
 * @property-read string|null $einddatetime  End date of team (with time).
 * @property-read string      $competitie    Competition name.
 * @property-read string      $geslacht      Team gender.
 * @property-read string      $categorie     Team category.
 * @property-read string|null $shirtkleur    Team shirt color.
 * @property-read string|null $broekkleur    Team pants color.
 * @property-read string|null $kousen        Team socks (color?).
 * @property-read string|null $omschrijving  Team description.
 * @property-read string|null $teamfoto      Team photo (binary format).
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?team-gegevens
 */
class TeamInfo extends AbstractItem
{
    const ARTICLE = 'team-gegevens';

    /**
     * Get team image.
     *
     * @return string|null
     */
    public function getImageTag()
    {
        if (!$this->teamfoto) {
            return null;
        }

        $alt = $this->teamnaam;
        $src = 'data:image/jpeg;base64,' . $this->teamfoto;

        return '<img alt="' . $alt . '" src="' . $src . '" />';
    }
}
