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
 * Class VotingDay
 */
class VotingDay extends AbstractEntity
{

    /**
     * Abstimmungstag
     *
     * @var \DateTime
     * @validate NotEmpty
     */
    protected $votingDate;

    /**
     * Archiviert
     *
     * @var boolean
     */
    protected $archived = FALSE;

    /**
     * Upload erlaubt
     *
     * @var boolean
     */
    protected $uploadAllowed = FALSE;

    /**
     * Meta-Abstimmungsvorlagen
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\MetaVotingProposal>
     * @lazy
     */
    protected $metaVotingProposals;

    /**
     * __construct
     *
     * @return VotingDay
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        /**
         * Do not modify this method!
         * It will be rewritten on each save in the extension builder
         * You may modify the constructor of this class instead
         */
        $this->metaVotingProposals = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the votingDate
     *
     * @return \DateTime $votingDate
     */
    public function getVotingDate()
    {
        return $this->votingDate;
    }

    /**
     * Sets the votingDate
     *
     * @param \DateTime $votingDate
     * @return void
     */
    public function setVotingDate($votingDate)
    {
        $this->votingDate = $votingDate;
    }

    /**
     * Returns the archived
     *
     * @return boolean $archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Sets the archived
     *
     * @param boolean $archived
     * @return void
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * Returns the uploadAllowed
     *
     * @return boolean $uploadAllowed
     */
    public function getUploadAllowed()
    {
        return $this->uploadAllowed;
    }

    /**
     * Sets the uploadAllowed
     *
     * @param boolean $uploadAllowed
     * @return void
     */
    public function setUploadAllowed($uploadAllowed)
    {
        $this->uploadAllowed = $uploadAllowed;
    }

    /**
     * Returns the boolean state of archived
     *
     * @return boolean
     */
    public function isArchived()
    {
        return $this->getArchived();
    }

    /**
     * Adds a MetaVotingProposal
     *
     * @param \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal
     * @return void
     */
    public function addMetaVotingProposal(\Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal)
    {
        $this->metaVotingProposals->attach($metaVotingProposal);
    }

    /**
     * Removes a MetaVotingProposal
     *
     * @param \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposalToRemove The MetaVotingProposal to be removed
     * @return void
     */
    public function removeMetaVotingProposal(\Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposalToRemove)
    {
        $this->metaVotingProposals->detach($metaVotingProposalToRemove);
    }

    /**
     * Returns the metaVotingProposals
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\MetaVotingProposal> $metaVotingProposals
     */
    public function getMetaVotingProposals()
    {
        return $this->metaVotingProposals;
    }

    /**
     * Sets the metaVotingProposals
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\MetaVotingProposal> $metaVotingProposals
     * @return void
     */
    public function setMetaVotingProposals(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $metaVotingProposals)
    {
        $this->metaVotingProposals = $metaVotingProposals;
    }

}