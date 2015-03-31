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

class Party extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var \string
	 */
	protected $title;

	/**
	 * @var \string
	 */
	protected $shortTitle;

	/**
	 * @var \string
	 */
	protected $description;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $image;

	/**
	 * @var \string
	 */
	protected $facebookProfile;

	/**
	 * @var \string
	 */
	protected $website;

	/**
	 * @var \string
	 */
	protected $smartvoteId;

	/**
	 * @var \boolean
	 */
	protected $isYoungParty;

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getFacebookProfile() {
		return $this->facebookProfile;
	}

	/**
	 * @param string $facebookProfile
	 */
	public function setFacebookProfile($facebookProfile) {
		$this->facebookProfile = $facebookProfile;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	/**
	 * @return boolean
	 */
	public function isIsYoungParty() {
		return $this->isYoungParty;
	}

	/**
	 * @param boolean $isYoungParty
	 */
	public function setIsYoungParty($isYoungParty) {
		$this->isYoungParty = $isYoungParty;
	}

	/**
	 * @return string
	 */
	public function getShortTitle() {
		return $this->shortTitle;
	}

	/**
	 * @param string $shortTitle
	 */
	public function setShortTitle($shortTitle) {
		$this->shortTitle = $shortTitle;
	}

	/**
	 * @return string
	 */
	public function getSmartvoteId() {
		return $this->smartvoteId;
	}

	/**
	 * @param string $smartvoteId
	 */
	public function setSmartvoteId($smartvoteId) {
		$this->smartvoteId = $smartvoteId;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * @param string $website
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}

}
