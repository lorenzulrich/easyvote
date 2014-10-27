<?php
namespace Visol\Easyvote\Controller;

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
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * communityUserRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
	 * @inject
	 */
	protected $communityUserRepository;

	/**
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser|bool
	 */
	protected function getLoggedInUser() {
		if ((int)$GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
			$communityUser = $this->communityUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
			if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
				return $communityUser;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * TYPO3 user login
	 * @param string $username
	 * @return boolean
	 */
	public function loginUser($username) {
		$loginData = array();
		$loginData['uname'] = $username;
		$loginData['status'] = 'login';

		$GLOBALS['TSFE']->fe_user->checkPid = 0;
		$info = $GLOBALS['TSFE']->fe_user->getAuthInfoArray();
		$storagePid = $this->settings['userStoragePid'];
		$info['db_user']['checkPidList'] = 1;
		$info['db_user']['check_pid_clause'] = 'AND pid IN(' . $storagePid . ')';
		$user = $GLOBALS['TSFE']->fe_user->fetchUserRecord($info['db_user'], $loginData['uname']);
		$GLOBALS['TSFE']->fe_user->createUserSession($user);
		$GLOBALS['TSFE']->fe_user->loginSessionStarted = TRUE;
		$GLOBALS['TSFE']->fe_user->user = $GLOBALS['TSFE']->fe_user->fetchUserSession();
	}

}
?>