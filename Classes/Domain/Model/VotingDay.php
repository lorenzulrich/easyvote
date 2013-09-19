<?php
namespace Visol\Easyvote\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class VotingDay extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

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
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
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
	public function getVotingDate() {
		return $this->votingDate;
	}

	/**
	 * Sets the votingDate
	 *
	 * @param \DateTime $votingDate
	 * @return void
	 */
	public function setVotingDate($votingDate) {
		$this->votingDate = $votingDate;
	}

	/**
	 * Returns the archived
	 *
	 * @return boolean $archived
	 */
	public function getArchived() {
		return $this->archived;
	}

	/**
	 * Sets the archived
	 *
	 * @param boolean $archived
	 * @return void
	 */
	public function setArchived($archived) {
		$this->archived = $archived;
	}

	/**
	 * Returns the boolean state of archived
	 *
	 * @return boolean
	 */
	public function isArchived() {
		return $this->getArchived();
	}

	/**
	 * Adds a MetaVotingProposal
	 *
	 * @param \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal
	 * @return void
	 */
	public function addMetaVotingProposal(\Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal) {
		$this->metaVotingProposals->attach($metaVotingProposal);
	}

	/**
	 * Removes a MetaVotingProposal
	 *
	 * @param \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposalToRemove The MetaVotingProposal to be removed
	 * @return void
	 */
	public function removeMetaVotingProposal(\Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposalToRemove) {
		$this->metaVotingProposals->detach($metaVotingProposalToRemove);
	}

	/**
	 * Returns the metaVotingProposals
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\MetaVotingProposal> $metaVotingProposals
	 */
	public function getMetaVotingProposals() {
		return $this->metaVotingProposals;
	}

	/**
	 * Sets the metaVotingProposals
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\MetaVotingProposal> $metaVotingProposals
	 * @return void
	 */
	public function setMetaVotingProposals(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $metaVotingProposals) {
		$this->metaVotingProposals = $metaVotingProposals;
	}

}
?>