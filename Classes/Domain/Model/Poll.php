<?php
namespace Visol\Easyvote\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Poll
 * @package Visol\Easyvote\Domain\Model
 */
class Poll extends AbstractEntity
{

    /**
     * Meinung
     *
     * @var \integer
     * @validate NotEmpty
     */
    protected $value;

    /**
     * Abstimmungsvorlage
     *
     * @var \Visol\Easyvote\Domain\Model\VotingProposal
     * @lazy
     */
    protected $votingProposal;

    /**
     * Benutzer
     *
     * @var \Visol\Easyvote\Domain\Model\CommunityUser
     * @lazy
     */
    protected $communityUser;

    /**
     * @return \Visol\Easyvote\Domain\Model\VotingProposal
     */
    public function getVotingProposal()
    {
        return $this->votingProposal;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     */
    public function setVotingProposal($votingProposal)
    {
        $this->votingProposal = $votingProposal;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\CommunityUser
     */
    public function getCommunityUser()
    {
        return $this->communityUser;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
     */
    public function setCommunityUser($communityUser)
    {
        $this->communityUser = $communityUser;
    }

}
