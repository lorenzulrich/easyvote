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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Panel
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

//	/**
//	 * @var \Visol\EasyvoteEducation\Service\VotingService
//	 * @inject
//	 */
//	protected $votingService;

    /**
     * Comment
     *
     * @var string
     */
    protected $comment = '';

    /**
     * Date
     *
     * @var \DateTime
     */
    protected $date = NULL;

    /**
     * From time
     *
     * @var integer
     */
    protected $fromTime = NULL;

    /**
     * CommunityUser (owner)
     *
     * @var \Visol\Easyvote\Domain\Model\CommunityUser
     */
    protected $communityUser = NULL;

    /**
     * Location
     *
     * @var \Visol\EasyvoteLocation\Domain\Model\Location
     */
    protected $location = NULL;

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return CommunityUser
     */
    public function getCommunityUser()
    {
        return $this->communityUser;
    }

    /**
     * @param CommunityUser $communityUser
     */
    public function setCommunityUser($communityUser)
    {
        $this->communityUser = $communityUser;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * @param int $fromTime
     */
    public function setFromTime($fromTime)
    {
        $this->fromTime = $fromTime;
    }

    /**
     * @return \Visol\EasyvoteLocation\Domain\Model\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \Visol\EasyvoteLocation\Domain\Model\Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

}