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
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PartyController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\PartyRepository
	 * @inject
	 */
	protected $partyRepository;

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
		if (!$this->getLoggedInUser()) {
			$code = 401;
			$message = 'Authorization Required';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * Container for manage members feature
	 */
	public function manageMembersAction() {
	}

	/**
	 * Output of the filter box
	 * Filter by: query string (firstName, lastName, citySelection.name), kanton and status
	 */
	public function memberFilterAction() {
		$kantons = $this->kantonRepository->findAll();
		$this->view->assign('kantons', $kantons);
		$statusFilters = array(
			'politician' => LocalizationUtility::translate('party.memberFilter.filter.status.confirmed', 'easyvote'),
			'pendingPolitician' => LocalizationUtility::translate('party.memberFilter.filter.status.pending', 'easyvote')
		);
		$this->view->assign('statusFilters', $statusFilters);
	}

	/**
	 * List all members of a party filtered by an eventually provided demand
	 *
	 * @param array $demand
	 * @dontverifyrequesthash
	 * @return string
	 */
	public function listMembersByDemandAction($demand = NULL) {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser->isPartyAdministrator()) {

			// Party is a lazy property of CommunityUser
			if ($communityUser->getParty() instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
				$communityUser->getParty()->_loadRealInstance();
			}

			$this->view->assign('demand', $demand);
			$this->view->assign('communityUser', $communityUser);

			$allMembers = $this->communityUserRepository->findByParty($communityUser->getParty());
			$this->view->assign('allMembers', $allMembers);
			$pendingMembers = $this->communityUserRepository->findPendingPoliticiansByParty($communityUser->getParty());
			$this->view->assign('pendingMembers', $pendingMembers);
			$filteredMembers = $this->communityUserRepository->findPoliticiansByPartyAndDemand($communityUser->getParty(), $demand);
			$this->view->assign('filteredMembers', $filteredMembers);

		} else {
			// TODO not a party administrator
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

}
