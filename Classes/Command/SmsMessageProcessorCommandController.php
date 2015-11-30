<?php
namespace Visol\Easyvote\Command;

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
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class SmsMessageProcessorCommandController extends \Visol\Easyvote\Command\AbstractCommandController implements \Visol\Easyvote\Interfaces\MessageProcessorInterface
{

    const JOBTYPE = 1;

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
    public function queueWorkerCommand($itemsPerRun = 20)
    {
        $this->initializeCommand();

        $pendingJobs = $this->messagingJobRepository->findPendingJobs(SmsMessageProcessorCommandController::JOBTYPE, $itemsPerRun);

        foreach ($pendingJobs as $job) {
            $gatewayUrl = 'https://' . urlencode($this->extensionConfiguration['settings']['smsGatewayUsername']) . ':' . $this->extensionConfiguration['settings']['smsGatewayPassword'] . '@api.websms.com/rest/smsmessaging/simple';

            /** @var \Visol\Easyvote\Domain\Model\MessagingJob $job */
            $recipient = $job->getCommunityUser()->getTelephone();
            foreach ($this->extensionConfiguration['settings']['allowedPhoneNumberPrefixes'] as $key => $phoneNumberPrefix) {
                if (GeneralUtility::isFirstPartOfStr($recipient, $key)) {
                    $lengthOfPrefixAndNumber = $phoneNumberPrefix['lengthOfPrefixAndNumber'];
                    break;
                }
            }

            if (empty($recipient) || strlen($recipient) !== (int)$lengthOfPrefixAndNumber) {
                $job->setTimeError(new \DateTime());
                $job->setErrorCode(AbstractCommandController::ERRORCODE_SMSINVALIDNUMBER);
                $this->messagingJobRepository->update($job);
                continue;
            }
            $gatewayUrl .= '?recipientAddressList=' . $recipient;

            $renderedContent = $this->renderContentWithFluid($job->getContent(), $job->getCommunityUser(), $this->getFluidArgumentArrayFromMessagingJobProperties($job));

            $gatewayUrl .= '&messageContent=' . urlencode(utf8_decode($renderedContent));
            if ((int)$this->extensionConfiguration['settings']['smsGatewayTest'] === 1) {
                $gatewayUrl .= '&test=true';
                \TYPO3\CMS\Core\Utility\DebugUtility::debug(GeneralUtility::getUrl($gatewayUrl), 'SMS-Gateway-Testmodus');
            } else {
                $response = GeneralUtility::getUrl($gatewayUrl);
                if (GeneralUtility::isFirstPartOfStr($response, 'statusCode=2000')) {
                    $job->setTimeDistributed(new \DateTime());
                    $job->setProcessorResponse('Request: ' . $gatewayUrl . LF . 'Response: ' . $response);
                } else {
                    $job->setTimeError(new \DateTime());
                    $job->setErrorCode(AbstractCommandController::ERRORCODE_SMSGATEWAY);
                    $job->setProcessorResponse('Request: ' . $gatewayUrl . LF . 'Response: ' . $response);
                }
                $this->messagingJobRepository->update($job);
            }
            // don't "overload" the SMS gateway
            usleep(100000);
        }

    }


}