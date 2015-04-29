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

class MetaVotingProposal extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Titel intern
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $privateTitle;

	/**
	 * votingDay
	 *
	 * @var \Visol\Easyvote\Domain\Model\VotingDay
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
	 * @return \Visol\Easyvote\Domain\Model\VotingDay
	 */
	public function getVotingDay() {
		return $this->votingDay;
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