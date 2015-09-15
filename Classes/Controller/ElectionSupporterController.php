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
use Visol\Easyvote\Domain\Model\CommunityUser;

/**
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ElectionSupporterController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
	 * @inject
	 */
	protected $communityUserRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\ExtensionService
	 * @inject
	 */
	protected $extensionService;

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
	 * Container for election supporter directory feature
	 * @param string $follow
	 */
	public function electionSupporterDirectoryAction($follow = NULL) {
		if ($follow) {
			// User wants to follow a certain user
			$targetUserUid = base64_decode($follow);
			$this->view->assign('targetUserUid', $targetUserUid);

			// We also pass the user to the template to be able to provide open graph tags
			$topDisplayUser = $this->communityUserRepository->findByUid((int)$targetUserUid);
			$this->view->assign('topDisplayUser', $topDisplayUser);

			$shareUri = $this->uriBuilder->setTargetPageUid($this->settings['electionSupporterPid'])
				// TODO enable
				//->setAbsoluteUriScheme('https')
				->setUseCacheHash(FALSE)
				->setArguments(array('tx_easyvote_electionsupporterfunctions' => array('follow' => base64_encode($topDisplayUser->getUid()))))
				->setCreateAbsoluteUri(TRUE)->build();

			$this->view->assign('encodedShareUri', urlencode($shareUri));
			$this->view->assign('shareUri', $shareUri);
			$this->view->assign('frontendObject', $this->getFrontendObject());
		}
		$this->view->assign('language', $this->getFrontendObject()->sys_language_uid);
	}

	/**
	 * Output of the filter box
	 * Filter by: query string (firstName), city, kanton
	 */
	public function filterAction() {
		$kantons = $this->kantonRepository->findAll();
		$this->view->assign('demand', $this->getDemandFromSession(TRUE));
		$this->view->assign('kantons', $kantons);
	}

	/**
	 * List all election supporters filtered by an eventually provided demand
	 *
	 * @param array $demand
	 * @param boolean $moreResultsOnly
	 * @param string $topDisplay
	 * @dontverifyrequesthash
	 * @return string
	 */
	public function listByDemandAction($demand = NULL, $moreResultsOnly = FALSE, $topDisplay = NULL) {
		if ($demand) {
			// Save demand to user session
			$this->saveDemandInSession($demand);
		} else {
			// If no demand is passed and a demand is in the session, use it
			$demand = $this->getDemandFromSession();
		}

		$this->view->assign('demand', $demand);

		$authenticatedUser = $this->communityUserService->getCommunityUser();
		$this->view->assign('authenticatedUser', $authenticatedUser);

		// Check if the current user has a Wahlhelfer themselves
		$excludeSupporter = NULL;
		if (!$moreResultsOnly) {
			if ($authenticatedUser) {
				if (!is_null($authenticatedUser->getCommunityUser())) {
					$excludeSupporter = $authenticatedUser->getCommunityUser();
					// This is the Wahlhelfer of the current user
					$this->view->assign('userElectionSupporter', $excludeSupporter);
				}
			}
		}

		// Check if a user is requested to be displayed on top
		if (!$moreResultsOnly && $topDisplay) {
			// We only display it on top if the user doesn't already follow this user
			if (!($excludeSupporter && $excludeSupporter->getUid() === (int)$topDisplay)) {
				$topDisplayUser = $this->communityUserRepository->findByUid((int)$topDisplay);
				if ($topDisplayUser instanceof CommunityUser) {
					$this->view->assign('topDisplayUser', $topDisplayUser);
				}
			}
		}

		$filteredElectionSupporters = $this->communityUserRepository->findElectionSupporters($demand, $excludeSupporter);
		$this->view->assign('filteredElectionSupporters', $filteredElectionSupporters);

		if (!$moreResultsOnly) {
			// Results are filtered if there are more supporters in total than in the filtered result
			$allElectionSupporters = $this->communityUserRepository->findElectionSupporters(NULL, $excludeSupporter);
			$isFiltered = $allElectionSupporters->count() > $filteredElectionSupporters->count();
			$this->view->assign('isFiltered', $isFiltered);
		}

	}

	/**
	 * Access check
	 */
	public function initializeFollowAction() {
		if (!$this->communityUserService->getCommunityUser()) {
			$code = 401;
			$message = 'Authorization Required';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * Makes the authenticated user follow the given another user
	 *
	 * @param CommunityUser $object
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @return string
	 */
	public function followAction(CommunityUser $object) {
		$communityUser = $this->communityUserService->getCommunityUser();
		$communityUser->setCommunityUser($object);
		$this->communityUserRepository->update($communityUser);
		$this->persistenceManager->persistAll();
		$this->communityUserRepository->updateRelationCount('fe_users', 'community_user', 'followers', 'fe_users', array('disable', 'deleted'), array('disable', 'deleted'));
		$this->communityUserRepository->updateCachedFollowerRanks();
		return json_encode(array('namespace' => 'Easyvote', 'function' => 'getElectionSupporters', 'arguments' => 'scrollTop'));
	}

	/**
	 * Access check
	 */
	public function initializeUnfollowAction() {
		if (!$this->communityUserService->getCommunityUser()) {
			$code = 401;
			$message = 'Authorization Required';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * Removes the election supporter from the authenticated user
	 *
	 * @param CommunityUser $object
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @return string
	 */
	public function unfollowAction(CommunityUser $object) {
		$communityUser = $this->communityUserService->getCommunityUser();
		$communityUser->setCommunityUser(NULL);
		$this->communityUserRepository->update($communityUser);
		$this->persistenceManager->persistAll();
		$this->communityUserRepository->updateRelationCount('fe_users', 'community_user', 'followers', 'fe_users', array('disable', 'deleted'), array('disable', 'deleted'));
		$this->communityUserRepository->updateCachedFollowerRanks();
		return json_encode(array('namespace' => 'Easyvote', 'function' => 'getElectionSupporters', 'arguments' => 'scrollTop'));
	}

	/**
	 * Displays a wall with election supporters and instructions on how to become one
	 */
	public function wallAction() {
		$numberOfPicturesOnWall = 288;
		$electionSupporters = $this->communityUserRepository->findElectionSupportersForWall($numberOfPicturesOnWall);
		$this->view->assign('electionSupporters', $electionSupporters);
		$electionSupportersCount = $this->communityUserRepository->countElectionSupportersForWall();
		$this->view->assign('electionSupportersCount', $electionSupportersCount);
		$goal = 1000;
		$reachedPercentage = round(($electionSupportersCount / $goal * 100));
		$this->view->assign('reachedPercentage', $reachedPercentage);
		$missingElectionSupportersForWall = $numberOfPicturesOnWall - $electionSupportersCount;
		$missingElectionSupportersArray = array_fill(0, $missingElectionSupportersForWall, NULL);
		$this->view->assign('missingElectionSupporters', $missingElectionSupportersArray);
	}

	/**
	 * Gets the POST data from the requests and returns all data in the plugin namespace if defined
	 *
	 * @return array|null
	 */
	protected function getSubmittedData() {
		$data = [];
		$pluginNamespace = $this->extensionService->getPluginNamespace($this->request->getControllerExtensionName(), $this->request->getPluginName());
		$requestBody = file_get_contents('php://input');
		parse_str($requestBody, $data);
		return array_key_exists($pluginNamespace, $data) ? $data[$pluginNamespace] : NULL;
	}

	/**
	 * @param array $demand
	 */
	protected function saveDemandInSession($demand) {
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_easyvote_manageElectionSupportersDemand', serialize($demand));
		$GLOBALS['TSFE']->fe_user->sesData_change = TRUE;
		$GLOBALS['TSFE']->fe_user->storeSessionData();
	}

	/**
	 * @param bool $sanitized
	 * @return mixed
	 */
	protected function getDemandFromSession($sanitized = FALSE) {
		$demand = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_easyvote_manageElectionSupportersDemand'));
		if ($sanitized) {
			return $this->sanitizeDemand($demand);
		} else {
			return $demand;
		}
	}

	/**
	 * Sanitize the query of a given demand (strip tags, htmlspecialchars)
	 *
	 * @param $demand
	 * @return array
	 */
	protected function sanitizeDemand($demand) {
		if (is_array($demand) && array_key_exists('query', $demand)) {
			$demand['query'] = htmlspecialchars(strip_tags($demand['query']));
			return $demand;
		}
	}

}
