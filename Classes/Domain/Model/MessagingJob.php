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
class MessagingJob extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	const JOBTYPE_SMS = 1;
	const JOBTYPE_EMAIL = 2;

	/**
	 * Type of messaging job
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $type;

	/**
	 * Benutzer
	 *
	 * @var \Visol\Easyvote\Domain\Model\CommunityUser
	 */
	protected $communityUser;

	/**
	 * Recipient name
	 *
	 * @var \string
	 */
	protected $recipientName;

	/**
	 * Recipient e-mail
	 *
	 * @var \string
	 */
	protected $recipientEmail;

	/**
	 * Sender name
	 *
	 * @var \string
	 */
	protected $senderName;

	/**
	 * Sender e-mail
	 *
	 * @var \string
	 */
	protected $senderEmail;

	/**
	 * $returnPath
	 *
	 * @var \string
	 */
	protected $returnPath;

	/**
	 * $replyTo
	 *
	 * @var \string
	 */
	protected $replyTo;

	/**
	 * Subject for message
	 *
	 * @var \string
	 */
	protected $subject;

	/**
	 * Content for message
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $content;

	/**
	 * Distribution time
	 *
	 * @var \DateTime|NULL
	 */
	protected $distributionTime;

	/**
	 * The time it was distributed
	 *
	 * @var \DateTime|NULL
	 */
	protected $timeDistributed;

	/**
	 * The time an error occured
	 *
	 * @var \DateTime|NULL
	 */
	protected $timeError;

	/**
	 * Code of error
	 *
	 * @var \integer
	 */
	protected $errorCode;

	/**
	 * Response from message processor
	 *
	 * @var \string
	 */
	protected $processorResponse;

	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
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
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getDistributionTime() {
		return $this->distributionTime;
	}

	/**
	 * @param \DateTime|NULL $distributionTime
	 */
	public function setDistributionTime($distributionTime) {
		$this->distributionTime = $distributionTime;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getTimeDistributed() {
		return $this->timeDistributed;
	}

	/**
	 * @param \DateTime|NULL $timeDistributed
	 */
	public function setTimeDistributed($timeDistributed) {
		$this->timeDistributed = $timeDistributed;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getTimeError() {
		return $this->timeError;
	}

	/**
	 * @param \DateTime|NULL $timeError
	 */
	public function setTimeError($timeError) {
		$this->timeError = $timeError;
	}

	/**
	 * @return int
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}

	/**
	 * @param int $errorCode
	 */
	public function setErrorCode($errorCode) {
		$this->errorCode = $errorCode;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @return string
	 */
	public function getRecipientName() {
		return $this->recipientName;
	}

	/**
	 * @param string $recipientName
	 */
	public function setRecipientName($recipientName) {
		$this->recipientName = $recipientName;
	}

	/**
	 * @return string
	 */
	public function getRecipientEmail() {
		return $this->recipientEmail;
	}

	/**
	 * @param string $recipientEmail
	 */
	public function setRecipientEmail($recipientEmail) {
		$this->recipientEmail = $recipientEmail;
	}

	/**
	 * @return string
	 */
	public function getSenderName() {
		return $this->senderName;
	}

	/**
	 * @param string $senderName
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * @return string
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}

	/**
	 * @param string $senderEmail
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
	}

	/**
	 * @return string
	 */
	public function getReturnPath() {
		return $this->returnPath;
	}

	/**
	 * @param string $returnPath
	 */
	public function setReturnPath($returnPath) {
		$this->returnPath = $returnPath;
	}

	/**
	 * @return string
	 */
	public function getReplyTo() {
		return $this->replyTo;
	}

	/**
	 * @param string $replyTo
	 */
	public function setReplyTo($replyTo) {
		$this->replyTo = $replyTo;
	}

	/**
	 * @return string
	 */
	public function getProcessorResponse() {
		return $this->processorResponse;
	}

	/**
	 * @param string $processorResponse
	 */
	public function setProcessorResponse($processorResponse) {
		$this->processorResponse = $processorResponse;
	}

}
?>