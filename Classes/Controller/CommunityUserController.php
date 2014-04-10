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

}
?>