<?php
namespace Visol\Easyvote\Controller;

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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class EventController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Access check
	 */
	public function initializeAction() {
		if (!$this->communityUserService->getCommunityUser()) {
			$code = 401;
			$message = 'Authorization Required';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * action editMobilizations
	 * @ignorevalidation $event
	 */
	public function mobilizationsAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		$this->view->assign('communityUser', $communityUser);

		/** @var \Visol\Easyvote\Domain\Model\Event $event */
		$event = $this->eventRepository->findOneByCommunityUser($communityUser);
		if (!$event instanceof \Visol\Easyvote\Domain\Model\Event) {
			/** @var \Visol\Easyvote\Domain\Model\Event $event */
			$event = $this->objectManager->get('Visol\Easyvote\Domain\Model\Event');
			$event->setDate(new \DateTime('2015-10-08'));
		}
		$this->view->assign('event', $event);

		$shareUri = $this->uriBuilder->setTargetPageUid($this->settings['electionSupporterPid'])
			// TODO enable
			//->setAbsoluteUriScheme('https')
			->setUseCacheHash(FALSE)
			->setArguments(array('tx_easyvote_electionsupporterfunctions' => array('follow' => base64_encode($communityUser->getUid()))))
			->setCreateAbsoluteUri(TRUE)->build();

		$this->view->assign('encodedShareUri', urlencode($shareUri));
		$this->view->assign('shareUri', $shareUri);
	}

	/**
	 * Property mapping of date, fromTime
	 */
	protected function initializeSaveAction() {
		$propertyMappingConfiguration = $this->arguments['event']->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->forProperty('date')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.y');
		$propertyMappingConfiguration->forProperty('fromTime')->setTypeConverter($this->objectManager->get('Visol\\Easyvote\\Property\\TypeConverter\\TimestampConverter'))->setTypeConverterOption('Visol\\Easyvote\\Property\\TypeConverter\\TimestampConverter', \Visol\Easyvote\Property\TypeConverter\TimestampConverter::CONFIGURATION_DATE_FORMAT, 'H:i');
	}

	/**
	 * Save an event
	 *
	 * @param \Visol\Easyvote\Domain\Model\Event $event
	 */
	public function saveAction($event) {
		$communityUser = $this->communityUserService->getCommunityUser();
		$event->setCommunityUser($communityUser);
		if (!$event->getUid()) {
			$this->eventRepository->add($event);
		} else {
			$this->eventRepository->update($event);
		}
		$this->persistenceManager->persistAll();
		$this->communityUserRepository->updateRelationCount('tx_easyvote_domain_model_event', 'community_user', 'events', 'fe_users', array('deleted', 'disable'));
		$this->redirect('mobilizations');
	}

	/**
	 * Remove an event
	 *
	 * @param \Visol\Easyvote\Domain\Model\Event $event
	 */
	public function removeAction($event) {
		$this->eventRepository->remove($event);
		$communityUser = $this->communityUserService->getCommunityUser();
		$communityUser->getFollowers()->removeAll($communityUser->getFollowers());
		$this->communityUserRepository->update($communityUser);
		$this->persistenceManager->persistAll();
		$this->redirect('mobilizations');
	}

}