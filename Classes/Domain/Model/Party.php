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
 * Class Party
 */
class Party extends AbstractEntity
{

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
     * @var string
     */
    protected $ch2055;

    /**
     * @var string
     */
    protected $videoUrl;

    /**
     * @var string
     */
    protected $linkToTwitter;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $incumbentPoliticiansContent;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     * @lazy
     */
    protected $incumbentPoliticiansImages;

    /**
     * @var boolean
     */
    protected $easyvoteSupporter;

    /**
     * @var string
     */
    protected $positionRetirementProvision;

    /**
     * @var string
     */
    protected $positionEuropeanUnion;

    /**
     * @var string
     */
    protected $positionMigration;

    /**
     * @var string
     */
    protected $positionEnergy;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteSmartvote\Domain\Model\Candidate>
     * @lazy
     */
    protected $candidates;

    /**
     * UID of localized record, if applicable
     *
     * @var integer
     */
    protected $localizedUid;

    /**
     * __construct
     *
     * @return Party
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
        $this->incumbentPoliticiansImages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }

    /**
     * @param string $facebookProfile
     */
    public function setFacebookProfile($facebookProfile)
    {
        $this->facebookProfile = $facebookProfile;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return boolean
     */
    public function isIsYoungParty()
    {
        return $this->isYoungParty;
    }

    /**
     * @param boolean $isYoungParty
     */
    public function setIsYoungParty($isYoungParty)
    {
        $this->isYoungParty = $isYoungParty;
    }

    /**
     * @return string
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * @param string $shortTitle
     */
    public function setShortTitle($shortTitle)
    {
        $this->shortTitle = $shortTitle;
    }

    /**
     * @return string
     */
    public function getSmartvoteId()
    {
        return $this->smartvoteId;
    }

    /**
     * @param string $smartvoteId
     */
    public function setSmartvoteId($smartvoteId)
    {
        $this->smartvoteId = $smartvoteId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteSmartvote\Domain\Model\Candidate> $candidates
     */
    public function getCandidates()
    {
        return $this->candidates;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\EasyvoteSmartvote\Domain\Model\Candidate> $candidates
     */
    public function setCandidates($candidates)
    {
        $this->candidates = $candidates;
    }

    /**
     * @return string
     */
    public function getCh2055()
    {
        return $this->ch2055;
    }

    /**
     * @param string $ch2055
     */
    public function setCh2055($ch2055)
    {
        $this->ch2055 = $ch2055;
    }

    /**
     * @return boolean
     */
    public function isEasyvoteSupporter()
    {
        return $this->easyvoteSupporter;
    }

    /**
     * @param boolean $easyvoteSupporter
     */
    public function setEasyvoteSupporter($easyvoteSupporter)
    {
        $this->easyvoteSupporter = $easyvoteSupporter;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getIncumbentPoliticiansContent()
    {
        return $this->incumbentPoliticiansContent;
    }

    /**
     * @param string $incumbentPoliticiansContent
     */
    public function setIncumbentPoliticiansContent($incumbentPoliticiansContent)
    {
        $this->incumbentPoliticiansContent = $incumbentPoliticiansContent;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $incumbentPoliticiansImages
     */
    public function getIncumbentPoliticiansImages()
    {
        return $this->incumbentPoliticiansImages;
    }

    /**
     * @param mixed $incumbentPoliticiansImages
     */
    public function setIncumbentPoliticiansImages($incumbentPoliticiansImages)
    {
        $this->incumbentPoliticiansImages = $incumbentPoliticiansImages;
    }

    /**
     * @return string
     */
    public function getLinkToTwitter()
    {
        return $this->linkToTwitter;
    }

    /**
     * @param string $linkToTwitter
     */
    public function setLinkToTwitter($linkToTwitter)
    {
        $this->linkToTwitter = $linkToTwitter;
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }

    /**
     * @param string $videoUrl
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;
    }

    /**
     * @return string
     */
    public function getPositionEnergy()
    {
        return $this->positionEnergy;
    }

    /**
     * @param string $positionEnergy
     */
    public function setPositionEnergy($positionEnergy)
    {
        $this->positionEnergy = $positionEnergy;
    }

    /**
     * @return string
     */
    public function getPositionEuropeanUnion()
    {
        return $this->positionEuropeanUnion;
    }

    /**
     * @param string $positionEuropeanUnion
     */
    public function setPositionEuropeanUnion($positionEuropeanUnion)
    {
        $this->positionEuropeanUnion = $positionEuropeanUnion;
    }

    /**
     * @return string
     */
    public function getPositionMigration()
    {
        return $this->positionMigration;
    }

    /**
     * @param string $positionMigration
     */
    public function setPositionMigration($positionMigration)
    {
        $this->positionMigration = $positionMigration;
    }

    /**
     * @return string
     */
    public function getPositionRetirementProvision()
    {
        return $this->positionRetirementProvision;
    }

    /**
     * @param string $positionRetirementProvision
     */
    public function setPositionRetirementProvision($positionRetirementProvision)
    {
        $this->positionRetirementProvision = $positionRetirementProvision;
    }

    /**
     * @return int
     */
    public function getLocalizedUid()
    {
        return $this->_localizedUid;
    }

}
