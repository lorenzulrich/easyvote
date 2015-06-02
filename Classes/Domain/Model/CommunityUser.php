<?php
namespace Visol\Easyvote\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
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

class CommunityUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * @var \Visol\Easyvote\Service\CommunityUserService
	 * @inject
	 */
	protected $communityUserService = NULL;

	/**
	 * @var \integer
	 */
	protected $gender;

	/**
	 * @var \DateTime|NULL
	 */
	protected $birthdate = NULL;

	/**
	 * @var string
	 */
	protected $authToken;

	/**
	 * Korrespondenzsprache
	 *
	 * @var \integer
	 */
	protected $userLanguage;

	/**
	 * Kanton
	 *
	 * @var \Visol\Easyvote\Domain\Model\Kanton
	 * @validate GenericObject
	 * @lazy
	 */
	protected $kanton;

	/**
	 * @var integer
	 */
	protected $age;

	/**
	 * @var integer
	 */
	protected $notificationMailActive;

	/**
	 * @var integer
	 */
	protected $notificationSmsActive;

	/**
	 * @var integer
	 */
	protected $disable;

	/**
	 * @validate EmailAddress
	 * @var string
	 */
	protected $email = '';

	/**
	 * @var string
	 * @transient
	 */
	protected $telephoneWithoutPrefix;

	/**
	 * @var string
	 * @transient
	 */
	protected $prefixCode;

	/**
	 * @var \Visol\Easyvote\Domain\Model\City
	 */
	protected $citySelection;

	/**
	 * Users that were subscribed for notifications by this user
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser>
	 * @lazy
	 */
	protected $notificationRelatedUsers;

	/**
	 * Panels added through EXT:easyvote_education
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteEducation\Domain\Model\Panel>
	 * @lazy
	 */
	protected $panels;

	/**
	 * Parent Community User
	 *
	 * @var \Visol\Easyvote\Domain\Model\CommunityUser
	 * @lazy
	 */
	protected $communityUser;

	/**
	 * @var \Visol\Easyvote\Domain\Model\Party
	 * @lazy
	 */
	protected $party = NULL;

	/**
	 * @var string
	 */
	protected $partyVerificationCode;

	/**
	 * @var string
	 */
	protected $organization;

	/**
	 * @var string
	 */
	protected $organizationWebsite;

	/**
	 * @var \Visol\Easyvote\Domain\Model\City
	 */
	protected $organizationCity;

	/**
	 * @var \boolean
	 * @transient
	 */
	protected $isTeacher;

	/**
	 * @var \boolean
	 * @transient
	 */
	protected $isPolitician;

	/**
	 * @var \boolean
	 * @transient
	 */
	protected $isPendingPolitician;

	/**
	 * @var \boolean
	 * @transient
	 */
	protected $isPendingPoliticianOrPolitician;

	/**
	 * @var \boolean
	 * @transient
	 */
	protected $isPartyAdministrator;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $falImage;

	const USERLANGUAGE_GERMAN = 1;
	const USERLANGUAGE_FRENCH = 2;
	const USERLANGUAGE_ITALIAN = 3;

	/**
	 * __construct
	 *
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		parent::__construct();
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->notificationRelatedUsers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->panels = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
	/**
	 * Adds a related user
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 * @return void
	 */
	public function addNotificationRelatedUser(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$this->notificationRelatedUsers->attach($communityUser);
	}

	/**
	 * Removes a related user
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 * @return void
	 */
	public function removeNotificationRelatedUser(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$this->notificationRelatedUsers->detach($communityUser);
	}

	/**
	 * Returns the related users
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser> $notificationRelatedUsers
	 */
	public function getNotificationRelatedUsers() {
		return $this->notificationRelatedUsers;
	}

	/**
	 * Sets the related users
	 *
	 * @param $ \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser> $notificationRelatedUsers
	 * @return void
	 */
	public function setNotificationRelatedUsers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $notificationRelatedUsers) {
		$this->notificationRelatedUsers = $notificationRelatedUsers;
	}

	/**
	 * @return mixed
	 */
	public function getUserLanguage() {
		return $this->userLanguage;
	}

	/**
	 * @param mixed $userLanguage
	 */
	public function setUserLanguage($userLanguage) {
		$this->userLanguage = $userLanguage;
	}

	/**
	 * @return integer
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param integer $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * @param \DateTime|NULL $birthdate
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;
	}

	/**
	 * @return \Visol\Easyvote\Domain\Model\Kanton
	 */
	public function getKanton() {
		return $this->kanton;
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
	 */
	public function setKanton($kanton) {
		$this->kanton = $kanton;
	}

	/**
	 * @return int
	 */
	public function getAge() {
		if ($this->getBirthdate() instanceof \DateTime) {
			$t = time();
			$birthdateTimestamp = $this->getBirthdate()->getTimestamp();
			$age = ($birthdateTimestamp < 0) ? ( $t + ($birthdateTimestamp * -1) ) : $t - $birthdateTimestamp;
			return floor($age/31536000);
		}
	}

	/**
	 * @return integer
	 */
	public function getNotificationMailActive() {
		return $this->notificationMailActive;
	}

	/**
	 * @param integer $notificationMailActive
	 */
	public function setNotificationMailActive($notificationMailActive) {
		$this->notificationMailActive = $notificationMailActive;
	}

	/**
	 * @return integer
	 */
	public function getNotificationSmsActive() {
		return $this->notificationSmsActive;
	}

	/**
	 * @param integer $notificationSmsActive
	 */
	public function setNotificationSmsActive($notificationSmsActive) {
		$this->notificationSmsActive = $notificationSmsActive;
	}

	/**
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser
	 */
	public function getCommunityUser() {
		return $this->communityUser;
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 */
	public function setCommunityUser($communityUser) {
		$this->communityUser = $communityUser;
	}

	/**
	 * @return string
	 */
	public function getTelephoneWithoutPrefix() {
		return $this->telephoneWithoutPrefix;
	}

	/**
	 * @param string $telephoneWithoutPrefix
	 */
	public function setTelephoneWithoutPrefix($telephoneWithoutPrefix) {
		$this->telephoneWithoutPrefix = $telephoneWithoutPrefix;
	}

	/**
	 * @return string
	 */
	public function getPrefixCode() {
		return $this->prefixCode;
	}

	/**
	 * @param string $prefixCode
	 */
	public function setPrefixCode($prefixCode) {
		$this->prefixCode = $prefixCode;
	}

	/**
	 * @return integer
	 */
	public function getDisable() {
		return $this->disable;
	}

	/**
	 * @param integer $disable
	 */
	public function setDisable($disable) {
		$this->disable = $disable;
	}

	/**
	 * @return \Visol\Easyvote\Domain\Model\City
	 */
	public function getCitySelection() {
		return $this->citySelection;
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\City $citySelection
	 */
	public function setCitySelection($citySelection) {
		$this->citySelection = $citySelection;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getFalImage() {
		return $this->falImage;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $falImage
	 */
	public function setFalImage($falImage) {
		$this->falImage = $falImage;
	}

	/**
	 * @return string
	 */
	public function getAuthToken() {
		return $this->authToken;
	}

	/**
	 * @param string $authToken
	 */
	public function setAuthToken($authToken) {
		$this->authToken = $authToken;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteEducation\Domain\Model\Panel> $panels
	 */
	public function getPanels() {
		return $this->panels;
	}

	/**
	 * @return boolean
	 */
	public function isPartyAdministrator() {
		return $this->communityUserService->hasRole($this, 'partyAdministrator');
	}

	/**
	 * @return boolean
	 */
	public function isPolitician() {
		return $this->communityUserService->hasRole($this, 'politician');
	}

	/**
	 * @return boolean
	 */
	public function isPendingPolitician() {
		return $this->communityUserService->hasRole($this, 'pendingPolitician');
	}

	/**
	 * @return boolean
	 */
	public function isTeacher() {
		return $this->communityUserService->hasRole($this, 'teacher');
	}

	/**
	 * @return boolean
	 */
	public function isPendingPoliticianOrPolitician() {
		return $this->isPendingPolitician() || $this->isPolitician();
	}

	/**
	 * @return Party
	 */
	public function getParty() {
		return $this->party;
	}

	/**
	 * @param Party $party
	 */
	public function setParty($party) {
		$this->party = $party;
	}

	/**
	 * @return mixed
	 */
	public function getPartyVerificationCode() {
		return $this->partyVerificationCode;
	}

	/**
	 * @param mixed $partyVerificationCode
	 */
	public function setPartyVerificationCode($partyVerificationCode) {
		$this->partyVerificationCode = $partyVerificationCode;
	}

	/**
	 * @return string
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * @param string $organization
	 */
	public function setOrganization($organization) {
		$this->organization = $organization;
	}

	/**
	 * @return City
	 */
	public function getOrganizationCity() {
		return $this->organizationCity;
	}

	/**
	 * @param City $organizationCity
	 */
	public function setOrganizationCity($organizationCity) {
		$this->organizationCity = $organizationCity;
	}

	/**
	 * @return mixed
	 */
	public function getOrganizationWebsite() {
		return $this->organizationWebsite;
	}

	/**
	 * @param mixed $organizationWebsite
	 */
	public function setOrganizationWebsite($organizationWebsite) {
		$this->organizationWebsite = $organizationWebsite;
	}

}
