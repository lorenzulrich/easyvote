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
	 * Bild (für Open Graph Tag)
	 *
	 * @var \string
	 */
	protected $image;

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
	 * Zusätzliche Informationen Überschrift
	 *
	 * @var \string
	 */
	protected $additionalInformationHeader;

	/**
	 * Zusätzliche Informationen Inhalt
	 *
	 * @var \string
	 */
	protected $additionalInformationContent;

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
	 * Anzahl der zustimmenden Kantone (Ständemehr)
	 *
	 * @var \string
	 */
	protected $kantonMajority;

	/**
	 * The permalink for sharing
	 *
	 * @var string
	 * @transient
	 */
	protected $permalink;

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
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param string $image
	 */
	public function setImage($image) {
		$this->image = $image;
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
	 * Returns the additionalInformationHeader
	 *
	 * @return \string $additionalInformationHeader
	 */
	public function getAdditionalInformationHeader() {
		return $this->additionalInformationHeader;
	}

	/**
	 * Sets the additionalInformationHeader
	 *
	 * @param \string $additionalInformationHeader
	 * @return void
	 */
	public function setAdditionalInformationHeader($additionalInformationHeader) {
		$this->additionalInformationHeader = $additionalInformationHeader;
	}

	/**
	 * Returns the additionalInformationContent
	 *
	 * @return \string $additionalInformationContent
	 */
	public function getAdditionalInformationContent() {
		return $this->additionalInformationContent;
	}

	/**
	 * Sets the additionalInformationContent
	 *
	 * @param \string $additionalInformationContent
	 * @return void
	 */
	public function setAdditionalInformationContent($additionalInformationContent) {
		$this->additionalInformationContent = $additionalInformationContent;
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

	/**
	 * Returns the kantonMajority
	 *
	 * @return \string $kantonMajority
	 */
	public function getKantonMajority() {
		return $this->kantonMajority;
	}

	/**
	 * Sets the kantonMajority
	 *
	 * @param \string $kantonMajority
	 * @return void
	 */
	public function setKantonMajority($kantonMajority) {
		$this->kantonMajority = $kantonMajority;
	}

	/**
	 * @return string
	 */
	public function getPermalink() {
		return $GLOBALS['TSFE']->tmpl->setup['config.']['baseURL'] . 'permalink/v/' . $this->getUid() . '-' . $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'];
	}

}
?>