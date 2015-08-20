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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
	 * Access check
	 */
	public function initializeAction() {
//		if (!$this->getLoggedInUser()) {
//			$code = 401;
//			$message = 'Authorization Required';
//			$this->response->setStatus($code, $message);
//			$this->response->shutdown();
//			die($message);
//		}
	}

	/**
	 * Container for election supporter directory feature
	 */
	public function electionSupporterDirectoryAction() {
		$this->view->assign('language', $this->getFrontendObject()->sys_language_uid);
	}

	/**
	 * Output of the filter box
	 * Filter by: query string (firstName, lastName, citySelection.name), kanton and status
	 */
	public function filterAction() {
		$kantons = $this->kantonRepository->findAll();
		$this->view->assign('demand', $this->getDemandFromSession(TRUE));
		// TODO filter by name
		// TODO filter by city
		$this->view->assign('kantons', $kantons);
	}

	/**
	 * List all election supporters filtered by an eventually provided demand
	 *
	 * @param array $demand
	 * @param boolean $moreResultsOnly
	 * @dontverifyrequesthash
	 * @return string
	 */
	public function listByDemandAction($demand = NULL, $moreResultsOnly = FALSE) {
		if ($demand) {
			// Save demand to user session
			$this->saveDemandInSession($demand);
		} else {
			// If no demand is passed and a demand is in the session, use it
			$demand = $this->getDemandFromSession();
		}

		$this->view->assign('demand', $demand);

		// Check if the current user has a Wahlhelfer themselves
		$excludeSupporter = NULL;
		if (!$moreResultsOnly) {
			if ($communityUser = $this->getLoggedInUser()) {
				if (!is_null($communityUser->getCommunityUser())) {
					$excludeSupporter = $communityUser->getCommunityUser();
					// This is the Wahlhelfer of the current user
					$this->view->assign('userElectionSupporter', $excludeSupporter);
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
