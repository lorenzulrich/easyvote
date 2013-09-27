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
class VotingProposal extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Kurztitel
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $shortTitle;

	/**
	 * Offizieller Titel
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $officialTitle;

	/**
	 * URL zu YouTube-Video
	 *
	 * @var \string
	 */
	protected $youtubeUrl;

	/**
	 * Ziel
	 *
	 * @var \string
	 */
	protected $goal;

	/**
	 * Ausgangslage
	 *
	 * @var \string
	 */
	protected $initialStatus;

	/**
	 * Was würde sich ändern?
	 *
	 * @var \string
	 */
	protected $consequence;

	/**
	 * Pro
	 *
	 * @var \string
	 */
	protected $proArguments;

	/**
	 * Kontra
	 *
	 * @var \string
	 */
	protected $contraArguments;

	/**
	 * Regierungsmeinung
	 *
	 * @var \string
	 */
	protected $governmentOpinion;

	/**
	 * Links
	 *
	 * @var \string
	 */
	protected $links;

	/**
	 * Zustimmung zur Vorlage (in %)
	 *
	 * @var \string
	 */
	protected $proposalApproval;

	/**
	 * Returns the shortTitle
	 *
	 * @return \string $shortTitle
	 */
	public function getShortTitle() {
		return $this->shortTitle;
	}

	/**
	 * Sets the shortTitle
	 *
	 * @param \string $shortTitle
	 * @return void
	 */
	public function setShortTitle($shortTitle) {
		$this->shortTitle = $shortTitle;
	}

	/**
	 * Returns the officialTitle
	 *
	 * @return \string $officialTitle
	 */
	public function getOfficialTitle() {
		return $this->officialTitle;
	}

	/**
	 * Sets the officialTitle
	 *
	 * @param \string $officialTitle
	 * @return void
	 */
	public function setOfficialTitle($officialTitle) {
		$this->officialTitle = $officialTitle;
	}

	/**
	 * Returns the youtubeUrl
	 *
	 * @return \string $youtubeUrl
	 */
	public function getYoutubeUrl() {
		return $this->youtubeUrl;
	}

	/**
	 * Sets the youtubeUrl
	 *
	 * @param \string $youtubeUrl
	 * @return void
	 */
	public function setYoutubeUrl($youtubeUrl) {
		$this->youtubeUrl = $youtubeUrl;
	}

	/**
	 * Returns the goal
	 *
	 * @return \string $goal
	 */
	public function getGoal() {
		return $this->goal;
	}

	/**
	 * Sets the goal
	 *
	 * @param \string $goal
	 * @return void
	 */
	public function setGoal($goal) {
		$this->goal = $goal;
	}

	/**
	 * Returns the initialStatus
	 *
	 * @return \string $initialStatus
	 */
	public function getInitialStatus() {
		return $this->initialStatus;
	}

	/**
	 * Sets the initialStatus
	 *
	 * @param \string $initialStatus
	 * @return void
	 */
	public function setInitialStatus($initialStatus) {
		$this->initialStatus = $initialStatus;
	}

	/**
	 * Returns the consequence
	 *
	 * @return \string $consequence
	 */
	public function getConsequence() {
		return $this->consequence;
	}

	/**
	 * Sets the consequence
	 *
	 * @param \string $consequence
	 * @return void
	 */
	public function setConsequence($consequence) {
		$this->consequence = $consequence;
	}

	/**
	 * Returns the proArguments
	 *
	 * @return \string $proArguments
	 */
	public function getProArguments() {
		return $this->proArguments;
	}

	/**
	 * Sets the proArguments
	 *
	 * @param \string $proArguments
	 * @return void
	 */
	public function setProArguments($proArguments) {
		$this->proArguments = $proArguments;
	}

	/**
	 * Returns the contraArguments
	 *
	 * @return \string $contraArguments
	 */
	public function getContraArguments() {
		return $this->contraArguments;
	}

	/**
	 * Sets the contraArguments
	 *
	 * @param \string $contraArguments
	 * @return void
	 */
	public function setContraArguments($contraArguments) {
		$this->contraArguments = $contraArguments;
	}

	/**
	 * Returns the governmentOpinion
	 *
	 * @return \string $governmentOpinion
	 */
	public function getGovernmentOpinion() {
		return $this->governmentOpinion;
	}

	/**
	 * Sets the governmentOpinion
	 *
	 * @param \string $governmentOpinion
	 * @return void
	 */
	public function setGovernmentOpinion($governmentOpinion) {
		$this->governmentOpinion = $governmentOpinion;
	}

	/**
	 * Returns the links
	 *
	 * @return \string $links
	 */
	public function getLinks() {
		return $this->links;
	}

	/**
	 * Sets the links
	 *
	 * @param \string $links
	 * @return void
	 */
	public function setLinks($links) {
		$this->links = $links;
	}

	/**
	 * Returns the proposalApproval
	 *
	 * @return \string $proposalApproval
	 */
	public function getProposalApproval() {
		return $this->proposalApproval;
	}

	/**
	 * Sets the proposalApproval
	 *
	 * @param \string $proposalApproval
	 * @return void
	 */
	public function setProposalApproval($proposalApproval) {
		$this->proposalApproval = $proposalApproval;
	}

}
?>