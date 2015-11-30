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
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Controller
 */
class AbstractCommandController extends CommandController
{

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var array
     */
    protected $extensionConfiguration;

    const ERRORCODE_SMSGATEWAY = 1;
    const ERRORCODE_SMSINVALIDNUMBER = 2;

    /**
     * @param $content
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
     * @param array $arguments
     * @return string
     */
    protected function renderContentWithFluid($content, \Visol\Easyvote\Domain\Model\CommunityUser $communityUser = NULL, $arguments = array())
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
        $standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
        $standaloneView->setFormat('html');

        if (substr($content, 0, 9) === 'Template:') {
            // it is a reference to a template file
            $templateFileName = GeneralUtility::trimExplode(':', $content);
            $templateFileName = $templateFileName[1];
            $templateRootPath = GeneralUtility::getFileAbsFileName($this->extensionConfiguration['view']['templateRootPath']);
            $templatePathAndFilename = $templateRootPath . 'Email/' . $templateFileName . '.html';
            $standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
        } else {
            // render the content of the content field :-) with Fluid
            $standaloneView->setTemplateSource($content);
        }

        $standaloneView->assign('parentUser', $communityUser->getCommunityUser());
        $standaloneView->assign('user', $communityUser);
        $standaloneView->assignMultiple($arguments);
        return $standaloneView->render();
    }

    /**
     * @param array $recipient
     * @param array $sender
     * @param $subject
     * @param $content
     * @param string $replyTo
     * @param string $returnPath
     * @return bool
     */
    protected function sendEmail(array $recipient, array $sender, $subject, $content, $replyTo = '', $returnPath = '')
    {
        /** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
        $message = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
        $message->setTo($recipient);
        $message->setFrom($sender);
        if (!empty($replyTo) && GeneralUtility::validEmail($replyTo)) {
            $message->addReplyTo($replyTo);
        }
        if (!empty($returnPath) && GeneralUtility::validEmail($returnPath)) {
            $message->setReturnPath($returnPath);
        }
        $message->setSubject($subject);
        $message->setBody($content, 'text/html');
        $message->send();
        return $message->isSent();
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob
     * @return array
     */
    protected function getFluidArgumentArrayFromMessagingJobProperties(\Visol\Easyvote\Domain\Model\MessagingJob $messagingJob)
    {
        return array(
            'recipientName' => $messagingJob->getRecipientName(),
            'recipientEmail' => $messagingJob->getRecipientEmail(),
            'senderName' => $messagingJob->getSenderName(),
            'senderEmail' => $messagingJob->getSenderEmail(),
        );
    }

    /**
     * constructor
     */
    public function initializeCommand()
    {
        $this->extensionConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'easyvote', 'easyvote');
    }

}