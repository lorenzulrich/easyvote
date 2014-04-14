<?php
namespace Visol\Easyvote\Controller;

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
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CommunityUserController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\VotingDayRepository
	 * @inject
	 */
	protected $votingDayRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
	 * @inject
	 */
	protected $frontendUserGroupRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\MessagingJobRepository
	 * @inject
	 */
	protected $messagingJobRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * action userOverview
	 *
	 * @return void
	 */
	public function userOverviewAction() {
		$communityUser = $this->getLoggedInUser();
		$this->view->assign('user', $communityUser);
	}

	/**
	 * action userFunctions
	 *
	 * @return void
	 */
	public function userFunctionsAction() {
	}

	/**
	 * action loginPanel
	 *
	 * @return void
	 */
	public function loginPanelAction() {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			$this->view->assign('user', $communityUser);
		}
	}

	/**
	 * action editProfile
	 */
	public function editProfileAction() {
		$communityUser = $this->getLoggedInUser();
		$kantons = $this->kantonRepository->findAll();

		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			$this->view->assign('user', $communityUser);
			$this->view->assign('kantons', $kantons);
		}
	}

	/**
	 * action updateProfile
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 */
	public function updateProfileAction(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$loggedInUser = $this->getLoggedInUser();
		/** Todo: Sanitize properties that should never be updated by the user. */
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$this->flashMessageContainer->add('<i class="icon icon-check-sign"></i> Dein Profil wurde aktualisiert!');
		}
		$this->redirect('editProfile');
	}

	/**
	 * action removeProfile
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 */
	public function removeProfileAction(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$loggedInUser = $this->getLoggedInUser();
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			$communityUser->setEmail('');
			$communityUser->setNotificationMailActive(0);
			$communityUser->setTelephone('');
			$communityUser->setNotificationSmsActive(0);
			$communityUser->setUsername('gelöschter Benutzer');
			$communityUser->setFirstName('gelöschter Benutzer');
			$communityUser->setLastName('');
			$communityUser->setAddress('');
			$communityUser->setZip('');
			$communityUser->setCity('');
			$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityUserGroupUid']);
			$communityUser->removeUsergroup($communityUserGroup);
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$votingProposalUri = $this->uriBuilder->setTargetPageUid($this->settings['siteHomePid'])->setArguments(array('logintype' => 'logout'))->setCreateAbsoluteUri(TRUE)->build();
			$this->redirectToUri($votingProposalUri);
		}
	}

	/**
	 * action editNotifications
	 */
	public function editNotificationsAction() {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			$this->view->assign('user', $communityUser);
		}
	}

	/**
	 * action updateNotifications
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 */
	public function updateNotificationsAction(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$loggedInUser = $this->getLoggedInUser();
		/** Todo: Sanitize properties that should never be updated by the user. */
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$this->flashMessageContainer->add('<i class="icon icon-check-sign"></i> Deine Vote-Wecker wurden aktualisiert!');
		}
		$this->redirect('editNotifications');
	}

	/**
	 * action editNotifications
	 */
	public function editMobilizationsAction() {
		$communityUser = $this->getLoggedInUser();
		$nextVotingDay = $this->votingDayRepository->findNextVotingDay();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			$this->view->assign('user', $communityUser);
			$this->view->assign('nextVotingDay', $nextVotingDay);
		}
	}

	/**
	 * action listMobilizedCommunityUsers
	 *
	 * @return string|boolean
	 */
	public function listMobilizedCommunityUsersAction() {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			$this->view->assign('user', $communityUser);
			$content = $this->view->render();
			return json_encode($content);
		} else {
			return json_encode(FALSE);
		}
	}

	/**
	 * action newMobilizedCommunityUser
	 *
	 * @return string
	 */
	public function newMobilizedCommunityUserAction() {
		$newCommunityUser = $this->objectManager->create('Visol\Easyvote\Domain\Model\CommunityUser');
		$this->view->assign('newCommunityUser', $newCommunityUser);
		$content = $this->view->render();
		return json_encode($content);
	}

	/**
	 * action createMobilizedCommunityUser
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $newCommunityUser
	 * @dontverifyrequesthash $newCommunityUser
	 * @return string
	 */
	public function createMobilizedCommunityUserAction(\Visol\Easyvote\Domain\Model\CommunityUser $newCommunityUser) {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			// user is authorized to add users to his profile
			if (GeneralUtility::validEmail($newCommunityUser->getEmail())) {
				if (!count($this->communityUserRepository->findByEmail($newCommunityUser->getEmail()))) {
					$notificationRelatedUserGroupUid = (int)$this->settings['notificationRelatedUserGroupUid'];
					$notificationRelatedUserGroup = $this->frontendUserGroupRepository->findByUid($notificationRelatedUserGroupUid);
					$newCommunityUser->addUsergroup($notificationRelatedUserGroup);
					$newCommunityUser->setCommunityUser($communityUser);
					$newCommunityUser->setNotificationMailActive(1);
					$newCommunityUser->setUsername($newCommunityUser->getEmail());
					$newCommunityUser->setPassword(md5(GeneralUtility::generateRandomBytes(40)));
					$newCommunityUser->setPid($this->settings['userStoragePid']);
					$this->communityUserRepository->add($newCommunityUser);
					$this->persistenceManager->persistAll();

					/** @var \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob */
					$standaloneView = $this->objectManager->create('TYPO3\CMS\Fluid\View\StandaloneView');
					$standaloneView->setFormat('html');
					$extbaseConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'easyvote', 'easyvote');
					$templateRootPath = GeneralUtility::getFileAbsFileName($extbaseConfiguration['view']['templateRootPath']);
					$templatePathAndFilename = $templateRootPath . 'Email/MobilizedWelcomeMail.html';
					$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
					$nextVotingDay = $this->votingDayRepository->findNextVotingDay();
					$standaloneView->assign('nextVotingDay', $nextVotingDay);
					$standaloneView->assign('parentUser', $communityUser);
					$standaloneView->assign('mobilizedUser', $newCommunityUser);
					$content = $standaloneView->render();
					$messagingJob = $this->objectManager->create('Visol\Easyvote\Domain\Model\MessagingJob');
					$messagingJob->setContent($content);
					$messagingJob->setSubject('Ein Vote-Wecker wurde für dich gestellt.');
					$messagingJob->setCommunityUser($newCommunityUser);
					$messagingJob->setDistributionTime(new \DateTime());
					$messagingJob->setType($messagingJob::JOBTYPE_EMAIL);
					$this->messagingJobRepository->add($messagingJob);

					return json_encode('Der Vote-Wecker wurde gestellt.');
				} else {
					return json_encode('Der Vote-Wecker konnte nicht gestellt werden, da für diese Person bereits ein Vote-Wecker gestellt wurde.');
				}
			} else {
				// e-mail invalid
				return json_encode('Der Vote-Wecker konnte nicht gestellt werden, da die E-Mail-Adresse ungültig war.');
			}
		} else {
			return json_encode(FALSE);
		}
	}

	/**
	 * action removeMobilizedCommunityUser
	 *
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $notificationRelatedUser
	 * @dontverifyrequesthash $notificationRelatedUser
	 * @return string
	 */
	public function removeMobilizedCommunityUserAction(\Visol\Easyvote\Domain\Model\CommunityUser $notificationRelatedUser) {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
			// user is logged in
			if ($notificationRelatedUser->getCommunityUser()->getUid() === $communityUser->getUid()) {
				// is it really a child of the logged in user
				// TODO in future: Check is user has change account to a real account and prevent deletion
				$this->communityUserRepository->remove($notificationRelatedUser);
				$this->persistenceManager->persistAll();
				return json_encode('Der Vote-Wecker-Kontakt wurde gelöscht.');
			} else {
				return json_encode('Du bist nicht berechtigt, diesen Vote-Wecker-Kontakt zu löschen.');
			}
		} else {
			return json_encode('Nicht eingeloggt.');
		}
	}

}
?>