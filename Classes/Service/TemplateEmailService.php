<?php
namespace Visol\Easyvote\Service;

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
use Visol\Easyvote\Domain\Model\CommunityUser;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class TemplateEmailService
 */
class TemplateEmailService
{

    /**
     * @var \Visol\Easyvote\Domain\Repository\MessagingJobRepository
     * @inject
     */
    protected $messagingJobRepository = NULL;

    /**
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     */
    protected $standaloneView;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    public $senderName = '';

    /**
     * @var string
     */
    public $senderEmail = '';

    /**
     * @var array
     */
    public $recipients;

    /**
     * Template Name without file ending. Must exist relative to Resources/Private/Email
     * e.g. MyMail
     *
     * @var string
     */
    public $templateName;

    /**
     * Extension name identical to extension key
     *
     * @var string
     */
    public $extensionName;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
        $this->standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
        $this->standaloneView->setFormat('html');
    }

    /**
     * Assign content to the Fluid standalone view
     *
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        $this->standaloneView->assign($key, $value);
    }

    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setExtensionName($extensionName)
    {
        $this->extensionName = $extensionName;
    }

    public function getExtensionName()
    {
        return $this->extensionName;
    }

    /**
     * Set a template for the StandaloneView
     *
     * @param string $templateName
     * @param string $extensionName
     * @param string $languageSuffix
     */
    public function setTemplate($templateName, $extensionName, $languageSuffix)
    {
        $templatePathAndFilename = $this->resolveViewFileForStandaloneView('Template', $templateName, $extensionName, $languageSuffix);
        $this->standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
    }

    /**
     * Put the message to the messaging queue
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function enqueue()
    {
        foreach ($this->recipients as $recipient) {
            /** @var \Visol\Easyvote\Domain\Model\CommunityUser $recipient */

            // Empty language suffix means default language (German)
            $languageSuffix = '';
            if ($recipient->getUserLanguage() !== CommunityUser::USERLANGUAGE_GERMAN) {
                if ($recipient->getUserLanguage() === CommunityUser::USERLANGUAGE_FRENCH) {
                    $languageSuffix = 'Fr';
                } elseif ($recipient->getUserLanguage() === CommunityUser::USERLANGUAGE_ITALIAN) {
                    $languageSuffix = 'It';
                }
            }
            $this->setTemplate($this->getTemplateName(), $this->getExtensionName(), $languageSuffix);
            $this->standaloneView->assign('communityUser', $recipient);
            $content = $this->standaloneView->render();
            $this->setSubject($languageSuffix);


            /** @var \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob */
            $messagingJob = $this->objectManager->get('Visol\Easyvote\Domain\Model\MessagingJob');
            $messagingJob->setSenderName($this->getSenderName());
            $messagingJob->setSenderEmail($this->getSenderEmail());
            // SPF
            $messagingJob->setReturnPath('info@easyvote.ch');
            $messagingJob->setContent($content);
            $messagingJob->setSubject($this->getSubject());
            $messagingJob->setCommunityUser($recipient);
            $messagingJob->setDistributionTime(new \DateTime());
            $messagingJob->setType($messagingJob::JOBTYPE_EMAIL);
            $this->messagingJobRepository->add($messagingJob);
        }
        $this->persistenceManager->persistAll();
    }

    /**
     * @param string $viewType One of Template, Partial, Layout
     * @param string $templateName Name of the template
     * @param string $extensionName
     * @param string $languageSuffix
     * @return string
     */
    public function resolveViewFileForStandaloneView($viewType, $templateName, $extensionName, $languageSuffix)
    {
        // filename is in format Email/MyTemplate[Fr].html
        $filename = 'Email/' . ucfirst($templateName) . $languageSuffix . '.html';
        $extensionName = str_replace('_', '', $extensionName);
        $extbaseConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, $extensionName);
        if (array_key_exists(lcfirst($viewType) . 'RootPath', $extbaseConfiguration['view'])) {
            // deprecated singular setting
            return GeneralUtility::getFileAbsFileName($extbaseConfiguration['view']['templateRootPath'] . $filename);
        } else {
            // new setting, reverse array (because highest priority is last)
            $viewTypeConfigurationArray = array_reverse($extbaseConfiguration['view'][lcfirst($viewType) . 'RootPaths']);
            // check if the requested file exists at location and return the first file found
            foreach ($viewTypeConfigurationArray as $viewTypeConfiguration) {
                if (file_exists(GeneralUtility::getFileAbsFileName($viewTypeConfiguration . $filename))) {
                    return GeneralUtility::getFileAbsFileName($viewTypeConfiguration . $filename);
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $languageSuffix
     */
    protected function setSubject($languageSuffix)
    {
        $key = 'email.subject.' . $this->getTemplateName() . $languageSuffix;
        $this->subject = LocalizationUtility::translate($key, $this->getExtensionName());
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $recipient
     */
    public function addRecipient($recipient)
    {
        $this->recipients[] = $recipient;
    }

}