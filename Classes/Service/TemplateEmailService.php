<?php
namespace Visol\Easyvote\Service;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

class TemplateEmailService {

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
	public $subject;

	/**
	 * @var string
	 */
	public $senderName = '';

	/**
	 * @var string
	 */
	public $senderEmail = '';

	/**
	 * @var \Visol\Easyvote\Domain\Model\CommunityUser
	 */
	public $recipient;

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

	public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
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
	public function assign($key, $value) {
		$this->standaloneView->assign($key, $value);
	}

	/**
	 * Set a template for the StandaloneView
	 *
	 * @param string $templatePathAndName
	 * @param string $extensionName
	 */
	public function setTemplate($templatePathAndName, $extensionName) {
		$templatePathAndFilename = $this->resolveViewFileForStandaloneView('Template', $templatePathAndName, $extensionName);
		$this->standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
	}

	/**
	 * Put the message to the messaging queue
	 *
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function enqueue() {
		$content = $this->standaloneView->render();
		/** @var \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob */
		$messagingJob = $this->objectManager->get('Visol\Easyvote\Domain\Model\MessagingJob');
		$messagingJob->setSenderName($this->getSenderName());
		$messagingJob->setSenderEmail($this->getSenderEmail());
		// SPF
		$messagingJob->setReturnPath('info@easyvote.ch');
		$messagingJob->setContent($content);
		$messagingJob->setSubject($this->getSubject());
		$messagingJob->setCommunityUser($this->getRecipient());
		$messagingJob->setDistributionTime(new \DateTime());
		$messagingJob->setType($messagingJob::JOBTYPE_EMAIL);
		$this->messagingJobRepository->add($messagingJob);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @param string $viewType One of Template, Partial, Layout
	 * @param string $filename Filename of requested template/partial/layout, may be prefixed with subfolders
	 * @param string $extensionName
	 * @return string
	 */
	public function resolveViewFileForStandaloneView($viewType, $filename, $extensionName) {
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
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $recipient
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

}