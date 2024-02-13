<?php

namespace PendoNL\ClubDataservice;

use PendoNL\ClubDataservice\Exception\InvalidResponseException;

/**
 * @property-read int|string $commissiecode The commission code id.
 *
 * @see https://dexels.github.io/navajofeeds-json-parser/article/?commissies
 */
class Committee extends AbstractItem
{
    const ARTICLE = 'commissies';

    /** @var CommitteeMember[]|array */
    private $members = [];

    /**
     * Get committee members.
     *
     * @param bool $showPicture Retrieve a picture of this member.
     *
     * @return CommitteeMember[]|array
     *
     * @throws InvalidResponseException
     */
    public function getMembers($showPicture = false)
    {
        if (count($this->members) != 0) {
            return $this->members;
        }

        $membersData = $this->api->request(CommitteeMember::ARTICLE, [
            'commissiecode' => (int)$this->commissiecode,
            'toonlidfoto' => $showPicture,
        ]);

        foreach ($membersData as $memberData) {
            $this->members[] = new CommitteeMember($this->api, $memberData);
        }

        return $this->members;
    }
}
