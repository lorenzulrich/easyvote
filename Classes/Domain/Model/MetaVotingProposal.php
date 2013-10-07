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
class MetaVotingProposal extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Titel intern
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $privateTitle;

	/**
	 * jobs
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\VotingDay>
	 */
	protected $votingDay;

	/**
	 * Typ
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $type;

	/**
	 * Reichweite
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $scope;

	/**
	 * Zustimmung zur Hauptvorlage in %
	 *
	 * @var \float
	 */
	protected $mainProposalApproval;

	/**
	 * Abstimmungsvorlagen
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\VotingProposal>
	 * @lazy
	 */
	protected $votingProposals;

	/**
	 * Kanton
	 *
	 * @var \Visol\Easyvote\Domain\Model\Kanton
	 * @lazy
	 */
	protected $kanton;

	/**
	 * __construct
	 *
	 * @return MetaVotingProposal
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
		$this->votingProposals = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the private title
	 *
	 * @return \string $privateTitle
	 */
	public function getPrivateTitle() {
		return $this->privateTitle;
	}

	/**
	 * Sets the private title
	 *
	 * @param \string $privateTitle
	 * @return void
	 */
	public function setPrivateTitle($privateTitle) {
		$this->privateTitle = $privateTitle;
	}

	/**
	 * Getter for voting day
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\VotingDay> $votingDay
	 */
	public function getVotingDay() {
		return $this->votingDay->toArray();
	}

	/**
	 * Returns the type
	 *
	 * @return \integer $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param \integer $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns the scope
	 *
	 * @return \integer $scope
	 */
	public function getScope() {
		return $this->scope;
	}

	/**
	 * Sets the scope
	 *
	 * @param \integer $scope
	 * @return void
	 */
	public function setScope($scope) {
		$this->scope = $scope;
	}

	/**
	 * Returns the mainProposalApproval
	 *
	 * @return \float $mainProposalApproval
	 */
	public function getMainProposalApproval() {
		return $this->mainProposalApproval;
	}

	/**
	 * Sets the mainProposalApproval
	 *
	 * @param \float $mainProposalApproval
	 * @return void
	 */
	public function setMainProposalApproval($mainProposalApproval) {
		$this->mainProposalApproval = $mainProposalApproval;
	}

	/**
	 * Adds a VotingProposal
	 *
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @return void
	 */
	public function addVotingProposal(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal) {
		$this->votingProposals->attach($votingProposal);
	}

	/**
	 * Removes a VotingProposal
	 *
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposalToRemove The VotingProposal to be removed
	 * @return void
	 */
	public function removeVotingProposal(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposalToRemove) {
		$this->votingProposals->detach($votingProposalToRemove);
	}

	/**
	 * Returns the votingProposals
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\VotingProposal> $votingProposals
	 */
	public function getVotingProposals() {
		return $this->votingProposals;
	}

	/**
	 * Sets the votingProposals
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\VotingProposal> $votingProposals
	 * @return void
	 */
	public function setVotingProposals(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $votingProposals) {
		$this->votingProposals = $votingProposals;
	}

	/**
	 * Returns the kanton
	 *
	 * @return \Visol\Easyvote\Domain\Model\Kanton $kanton
	 */
	public function getKanton() {
		return $this->kanton;
	}

	/**
	 * Sets the kanton
	 *
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
	 * @return void
	 */
	public function setKanton(\Visol\Easyvote\Domain\Model\Kanton $kanton) {
		$this->kanton = $kanton;
	}

}
?>