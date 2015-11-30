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
use Visol\Easyvote\Interfaces\MessageProcessorInterface;

/**
 * Controller
 */
class EmailMessageProcessorCommandController extends AbstractCommandController implements MessageProcessorInterface
{

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
    public function queueWorkerCommand($itemsPerRun = 20)
    {
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
