<?php
namespace Visol\Easyvote\Command;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class EmailMessageProcessorCommandController extends \Visol\Easyvote\Command\AbstractCommandController implements \Visol\Easyvote\Interfaces\MessageProcessorInterface {

	const JOBTYPE = 2;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\MessagingJobRepository
	 * @inject
	 */
	protected $messagingJobRepository;

	/**
	 * Process SMS message queue
	 *
	 * @param int $itemsPerRun
	 */
	public function queueWorkerCommand($itemsPerRun = 20) {
		$this->initializeCommand();

		$pendingJobs = $this->messagingJobRepository->findPendingJobs(EmailMessageProcessorCommandController::JOBTYPE, $itemsPerRun);

		foreach ($pendingJobs as $job) {
			/** @var \Visol\Easyvote\Domain\Model\MessagingJob $job */

			if ($job->getCommunityUser() instanceof \Visol\Easyvote\Domain\Model\CommunityUser && GeneralUtility::validEmail($job->getCommunityUser()->getEmail())) {
				$emailContent = $this->renderContentWithFluid($job->getContent(), $job->getCommunityUser(), $this->getFluidArgumentArrayFromMessagingJobProperties($job));

				// sender in job overrides sender from configuration
				$senderName = $job->getSenderName() !== '' ? $job->getSenderName() : $this->extensionConfiguration['settings']['senderName'];
				$senderEmail = $job->getSenderEmail() !== '' && GeneralUtility::validEmail($job->getSenderEmail()) ? $job->getSenderEmail() : $this->extensionConfiguration['settings']['senderEmail'];
				$sender = array($senderEmail => $senderName);

				// recipient in job overwrites recipient from communityUser
				$recipientName = $job->getRecipientName() !== '' ? $job->getRecipientName() : $job->getCommunityUser()->getFirstName() . ' ' . $job->getCommunityUser()->getLastName();
				$recipientEmail = $job->getRecipientEmail() !== '' && GeneralUtility::validEmail($job->getRecipientEmail()) ? $job->getRecipientEmail() : $job->getCommunityUser()->getEmail();
				$recipient = array($recipientEmail => $recipientName);

				$returnPath = $job->getReturnPath() !== '' && GeneralUtility::validEmail($job->getReturnPath()) ? $job->getReturnPath() : $this->extensionConfiguration['settings']['senderEmail'];

				$success = $this->sendEmail($recipient, $sender, $job->getSubject(), $emailContent, $job->getReplyTo(), $returnPath);
				if ($success) {
					$job->setTimeDistributed(new \DateTime());
				} else {
					$job->setTimeError(new \DateTime());
				}
				$this->messagingJobRepository->update($job);
			} else {
				// user is no longer present or has no e-mail address, delete job
				$this->messagingJobRepository->remove($job);
			}
		}

	}

}
