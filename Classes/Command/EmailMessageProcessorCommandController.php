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

		$pendingJobs = $this->messagingJobRepository->findPendingJobs(EmailMessageProcessorCommandController::JOBTYPE);

		foreach ($pendingJobs as $job) {
			/** @var \Visol\Easyvote\Domain\Model\MessagingJob $job */
			$emailContent = $this->renderContentWithFluid($job->getContent(), $job->getCommunityUser());
			$sender = array($this->extensionConfiguration['settings']['senderEmail'] => $this->extensionConfiguration['settings']['senderName']);
			$recipient = array($job->getCommunityUser()->getEmail() => $job->getCommunityUser()->getFirstName() . ' ' . $job->getCommunityUser()->getLastName());
			$success = $this->sendEmail($recipient, $sender, $job->getSubject(), $emailContent);
			if ($success) {
				$job->setTimeDistributed(new \DateTime());
			} else {
				$job->setTimeError(new \DateTime());
			}
		}

	}



}
?>