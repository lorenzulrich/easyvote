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
class SmsMessageProcessorCommandController extends \Visol\Easyvote\Command\AbstractCommandController implements \Visol\Easyvote\Interfaces\MessageProcessorInterface {

	const JOBTYPE = 1;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\MessagingJobRepository
	 * @inject
	 */
	protected $messagingJobRepository;

	/**
	 * @param \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob
	 * @return mixed
	 */
	public function writeLog(\Visol\Easyvote\Domain\Model\MessagingJob $messagingJob) {
		return TRUE;
	}

	/**
	 * Process SMS message queue
	 *
	 * @param int $itemsPerRun
	 */
	public function queueWorkerCommand($itemsPerRun = 20) {
		$this->initializeCommand();

		$pendingJobs = $this->messagingJobRepository->findPendingJobs(SmsMessageProcessorCommandController::JOBTYPE);

		$gatewayUrl = 'https://' . urlencode($this->extensionConfiguration['settings']['smsGatewayUsername']) . ':' . $this->extensionConfiguration['settings']['smsGatewayPassword'] . '@api.websms.com/rest/smsmessaging/simple';

		foreach ($pendingJobs as $job) {
			/** @var \Visol\Easyvote\Domain\Model\MessagingJob $job */
			// TODO parse
			$recipient = $job->getCommunityUser()->getTelephone();
			$gatewayUrl .= '?recipientAddressList=' . $recipient;
			$gatewayUrl .= '&messageContent=' . urlencode($job->getContent());
			if ((int)$this->extensionConfiguration['settings']['smsGatewayTest'] === 1) {
				$gatewayUrl .= '&test=true';
				\TYPO3\CMS\Core\Utility\DebugUtility::debug(GeneralUtility::getUrl($gatewayUrl), 'SMS-Gateway-Testmodus');
			} else {
				$response = GeneralUtility::getUrl($gatewayUrl);
				if (GeneralUtility::isFirstPartOfStr($response, 'statusCode=2000')) {
					$job->setTimeDistributed(new \DateTime());
				} else {
					$job->setTimeError(new \DateTime());
					$job->setErrorCode(AbstractCommandController::ERRORCODE_SMS);
				}
				$this->messagingJobRepository->update($job);
			}

		}

	}

}
?>