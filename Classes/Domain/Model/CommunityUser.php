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

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CommunityUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * @var \integer
	 */
	protected $gender;

	/**
	 * @var \DateTime|NULL
	 */
	protected $birthdate = NULL;

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
	 * Users that were subscribed for notifications by this user
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser>
	 * @lazy
	 */
	protected $notificationRelatedUsers;

	/**
	 * Parent Community User
	 *
	 * @var \Visol\Easyvote\Domain\Model\CommunityUser
	 * @lazy
	 */
	protected $communityUser;

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

}
?>