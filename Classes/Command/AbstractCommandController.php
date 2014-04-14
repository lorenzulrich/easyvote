<?php
namespace Visol\Easyvote\Command;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AbstractCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $extensionConfiguration;

	/**
	 * @param $content
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 * @return string
	 */
	protected function renderContentWithFluid($content, \Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
		$standaloneView = $this->objectManager->create('TYPO3\CMS\Fluid\View\StandaloneView');
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

		$standaloneView->assign('user', $communityUser);
		return $standaloneView->render();
	}

	/**
	 * @param array $recipient
	 * @param array $sender
	 * @param $subject
	 * @param $content
	 * @return bool
	 */
	protected function sendEmail(array $recipient, array $sender, $subject, $content) {
		/** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
		$message = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
		$message->setTo($recipient);
		$message->setFrom($sender);
		$message->setSubject($subject);
		$message->setBody($content, 'text/html');
		$message->send();
		return $message->isSent();
	}

	/**
	 * constructor
	 */
	public function initializeCommand() {
		$this->extensionConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'easyvote', 'easyvote');
	}

}
?>