<?php
namespace Visol\Easyvote\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Visol\EasyvoteSmartvote\Domain\Model\PersonalElectionList;

/**
 * Class CommunityUser
 */
class CommunityUser extends FrontendUser
{

    const USERLANGUAGE_GERMAN = 1;
    const USERLANGUAGE_FRENCH = 2;
    const USERLANGUAGE_ITALIAN = 3;

    const NEWSLETTER_VOTING = 'notificationMailActive';
    const NEWSLETTER_COMMUNITY = 'communityNewsMailActive';

    /**
     * @var \Visol\Easyvote\Service\CommunityUserService
     * @inject
     */
    protected $communityUserService = NULL;

    /**
     * @var string
     * @transient
     */
    protected $uidBase64;

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
    protected $communityNewsMailActive;

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
     * @var string
     * @transient
     */
    protected $formattedTelephone;

    /**
     * @var \Visol\Easyvote\Domain\Model\City
     */
    protected $citySelection;

    /**
     * Followers of this user
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser>
     * @lazy
     */
    protected $followers;

    /**
     * Events of this user
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Event>
     * @lazy
     */
    protected $events;

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
     * Needs to be eager loading because _isDirty is incorrect for LazyLoading, see CommunityUserController:updateAction
     *
     * @var \Visol\Easyvote\Domain\Model\Party
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
     * @var string
     */
    protected $educationType;

    /**
     * @var string
     */
    protected $educationInstitution;

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
     * @var \boolean
     * @transient
     */
    protected $isCommunityFacebookUser;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $falImage;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Visol\EasyvoteSmartvote\Domain\Model\PersonalElectionList>
     */
    protected $personalElectionLists;

    /**
     * Protect privacy of user
     *
     * @var boolean
     */
    protected $privacyProtection;

    /**
     * Is a VIP
     *
     * @var boolean
     */
    protected $vip;

    /**
     * Rank of all users having followers
     *
     * @var integer
     */
    protected $cachedFollowerRankCh;

    /**
     * Rank of all users having followers in the user's canton
     *
     * @var integer
     */
    protected $cachedFollowerRankCanton;

    /**
     * Rank of all VIP users having followers
     *
     * @var integer
     */
    protected $cachedFollowerRankVip;

    /**
     * __construct
     *
     * @return \Visol\Easyvote\Domain\Model\CommunityUser
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        parent::__construct();
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->followers = new ObjectStorage();
        $this->events = new ObjectStorage();
        $this->panels = new ObjectStorage();
        $this->personalElectionLists = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getUidBase64()
    {
        return base64_encode($this->uid);
    }

    /**
     * Adds a follower
     *
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
     * @return void
     */
    public function addFollower(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser)
    {
        $this->followers->attach($communityUser);
    }

    /**
     * Removes a follower
     *
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
     * @return void
     */
    public function removeFollower(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser)
    {
        $this->followers->detach($communityUser);
    }

    /**
     * Returns the followers
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser> $followers
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Sets the followers
     *
     * @param $followers \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\CommunityUser>
     * @return void
     */
    public function setFollowers(ObjectStorage $followers)
    {
        $this->followers = $followers;
    }

    /**
     * Returns the events
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Event> $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Sets the events
     *
     * @param $events \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Event>
     * @return void
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return mixed
     */
    public function getUserLanguage()
    {
        return $this->userLanguage;
    }

    /**
     * @param mixed $userLanguage
     */
    public function setUserLanguage($userLanguage)
    {
        $this->userLanguage = $userLanguage;
    }

    /**
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param integer $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return \DateTime|NULL
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime|NULL $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\Kanton
     */
    public function getKanton()
    {
        return $this->kanton;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
     */
    public function setKanton($kanton)
    {
        $this->kanton = $kanton;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        if ($this->getBirthdate() instanceof \DateTime) {
            $t = time();
            $birthdateTimestamp = $this->getBirthdate()->getTimestamp();
            $age = ($birthdateTimestamp < 0) ? ($t + ($birthdateTimestamp * -1)) : $t - $birthdateTimestamp;
            return floor($age / 31536000);
        }
    }

    /**
     * @return integer
     */
    public function getNotificationMailActive()
    {
        return $this->notificationMailActive;
    }

    /**
     * @param integer $notificationMailActive
     */
    public function setNotificationMailActive($notificationMailActive)
    {
        $this->notificationMailActive = $notificationMailActive;
    }

    /**
     * @return integer
     */
    public function getNotificationSmsActive()
    {
        return $this->notificationSmsActive;
    }

    /**
     * @param integer $notificationSmsActive
     */
    public function setNotificationSmsActive($notificationSmsActive)
    {
        $this->notificationSmsActive = $notificationSmsActive;
    }

    /**
     * @return integer
     */
    public function getCommunityNewsMailActive()
    {
        return $this->communityNewsMailActive;
    }

    /**
     * @param integer $communityNewsMailActive
     */
    public function setCommunityNewsMailActive($communityNewsMailActive)
    {
        $this->communityNewsMailActive = $communityNewsMailActive;
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

    /**
     * @return string
     */
    public function getTelephoneWithoutPrefix()
    {
        return $this->telephoneWithoutPrefix;
    }

    /**
     * @param string $telephoneWithoutPrefix
     */
    public function setTelephoneWithoutPrefix($telephoneWithoutPrefix)
    {
        $this->telephoneWithoutPrefix = $telephoneWithoutPrefix;
    }

    /**
     * @return string
     */
    public function getPrefixCode()
    {
        return $this->prefixCode;
    }

    /**
     * @param string $prefixCode
     */
    public function setPrefixCode($prefixCode)
    {
        $this->prefixCode = $prefixCode;
    }

    /**
     * @return string
     */
    public function getFormattedTelephone()
    {
        if (GeneralUtility::isFirstPartOfStr($this->telephone, '41')) {
            return '0' . substr($this->telephone, 2);
        }
    }

    /**
     * @return integer
     */
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * @param integer $disable
     */
    public function setDisable($disable)
    {
        $this->disable = $disable;
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\City
     */
    public function getCitySelection()
    {
        return $this->citySelection;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\City $citySelection
     */
    public function setCitySelection($citySelection)
    {
        $this->citySelection = $citySelection;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getFalImage()
    {
        return $this->falImage;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $falImage
     */
    public function setFalImage($falImage)
    {
        $this->falImage = $falImage;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteEducation\Domain\Model\Panel> $panels
     */
    public function getPanels()
    {
        return $this->panels;
    }

    /**
     * @return boolean
     */
    public function isPartyAdministrator()
    {
        return $this->communityUserService->hasRole($this, 'partyAdministrator');
    }

    /**
     * @return boolean
     */
    public function isPolitician()
    {
        return $this->communityUserService->hasRole($this, 'politician');
    }

    /**
     * @return boolean
     */
    public function isPendingPolitician()
    {
        return $this->communityUserService->hasRole($this, 'pendingPolitician');
    }

    /**
     * @return boolean
     */
    public function isTeacher()
    {
        return $this->communityUserService->hasRole($this, 'teacher');
    }

    /**
     * @return boolean
     */
    public function isPendingPoliticianOrPolitician()
    {
        return $this->isPendingPolitician() || $this->isPolitician();
    }

    /**
     * @return boolean
     */
    public function isCommunityFacebookUser()
    {
        return $this->communityUserService->hasRole($this, 'communityFacebook');
    }

    /**
     * This getter is needed for the lazy loading parent user ($communityUser)
     * @return boolean
     */
    public function getCommunityFacebookUser()
    {
        return $this->communityUserService->hasRole($this, 'communityFacebook');
    }

    /**
     * @return Party
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     * @param Party $party
     */
    public function setParty($party)
    {
        $this->party = $party;
    }

    /**
     * @return mixed
     */
    public function getPartyVerificationCode()
    {
        return $this->partyVerificationCode;
    }

    /**
     * @param mixed $partyVerificationCode
     */
    public function setPartyVerificationCode($partyVerificationCode)
    {
        $this->partyVerificationCode = $partyVerificationCode;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return City
     */
    public function getOrganizationCity()
    {
        return $this->organizationCity;
    }

    /**
     * @param City $organizationCity
     */
    public function setOrganizationCity($organizationCity)
    {
        $this->organizationCity = $organizationCity;
    }

    /**
     * @return mixed
     */
    public function getOrganizationWebsite()
    {
        return $this->organizationWebsite;
    }

    /**
     * @param mixed $organizationWebsite
     */
    public function setOrganizationWebsite($organizationWebsite)
    {
        $this->organizationWebsite = $organizationWebsite;
    }

    /**
     * @return string
     */
    public function getEducationInstitution()
    {
        return $this->educationInstitution;
    }

    /**
     * @param string $educationInstitution
     */
    public function setEducationInstitution($educationInstitution)
    {
        $this->educationInstitution = $educationInstitution;
    }

    /**
     * @return string
     */
    public function getEducationType()
    {
        return $this->educationType;
    }

    /**
     * @param string $educationType
     */
    public function setEducationType($educationType)
    {
        $this->educationType = $educationType;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getPersonalElectionLists()
    {
        return $this->personalElectionLists;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $personalElectionList
     * @return $this
     */
    public function setPersonalElectionLists($personalElectionList)
    {
        $this->personalElectionLists = $personalElectionList;
        return $this;
    }

    /**
     * @param \Visol\EasyvoteSmartvote\Domain\Model\PersonalElectionList $personalElectionList
     * @return void
     */
    public function addPersonalElectionList(PersonalElectionList $personalElectionList)
    {
        $this->personalElectionLists->attach($personalElectionList);
    }

    /**
     * @param \Visol\EasyvoteSmartvote\Domain\Model\PersonalElectionList $personalElectionList
     * @return void
     */
    public function removePersonalElectionList(PersonalElectionList $personalElectionList)
    {
        $this->personalElectionLists->detach($personalElectionList);
    }

    /**
     * @return boolean
     */
    public function isPrivacyProtection()
    {
        return $this->privacyProtection;
    }

    /**
     * This getter is needed for the lazy loading parent user ($communityUser)
     * @return boolean
     */
    public function getPrivacyProtection()
    {
        return $this->privacyProtection;
    }

    /**
     * @param boolean $privacyProtection
     */
    public function setPrivacyProtection($privacyProtection)
    {
        $this->privacyProtection = $privacyProtection;
    }

    /**
     * @return int
     */
    public function getCachedFollowerRankCanton()
    {
        return $this->cachedFollowerRankCanton;
    }

    /**
     * @param int $cachedFollowerRankCanton
     */
    public function setCachedFollowerRankCanton($cachedFollowerRankCanton)
    {
        $this->cachedFollowerRankCanton = $cachedFollowerRankCanton;
    }

    /**
     * @return int
     */
    public function getCachedFollowerRankCh()
    {
        return $this->cachedFollowerRankCh;
    }

    /**
     * @param int $cachedFollowerRankCh
     */
    public function setCachedFollowerRankCh($cachedFollowerRankCh)
    {
        $this->cachedFollowerRankCh = $cachedFollowerRankCh;
    }

    /**
     * @return int
     */
    public function getCachedFollowerRankVip()
    {
        return $this->cachedFollowerRankVip;
    }

    /**
     * @param int $cachedFollowerRankVip
     */
    public function setCachedFollowerRankVip($cachedFollowerRankVip)
    {
        $this->cachedFollowerRankVip = $cachedFollowerRankVip;
    }

    /**
     * @return boolean
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * @param boolean $vip
     */
    public function setVip($vip)
    {
        $this->vip = $vip;
    }

}
