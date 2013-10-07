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

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class MetaVotingProposalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * metaVotingProposalRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\MetaVotingProposalRepository
	 * @inject
	 */
	protected $metaVotingProposalRepository;

	/**
	 * votingDayRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\VotingDayRepository
	 * @inject
	 */
	protected $votingDayRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$metaVotingProposals = $this->metaVotingProposalRepository->findAll();
		$this->view->assign('metaVotingProposals', $metaVotingProposals);
	}

	/**
	 * action archive
	 *
	 * @return void
	 */
	public function archiveAction() {

		// generate list of all years with votings
		$votingDays = $this->votingDayRepository->findAll();
		$votingYears = array();
		foreach ($votingDays as $votingDay) {
			$votingYear = date('Y', $votingDay->getVotingDate()->getTimestamp());
			$votingYears[$votingYear] = $votingYear;
		}
		$this->view->assign('years', $votingYears);

		// generate list of all Kantons with votings
		$metaVotingProposals = $this->metaVotingProposalRepository->findAll();
		$kantons = array();
		foreach ($metaVotingProposals as $metaVotingProposal) {
			if ($metaVotingProposal->getScope() === 2) {
				// Kantonale Abstimmungen
				$kantons[$metaVotingProposal->getKanton()->getUid()] = $metaVotingProposal->getKanton()->getAbbreviation();
			}
		}
		$this->view->assign('kantons', $kantons);

		// perform search
		$request = $this->request->getArguments();
		$demand = array();
		$filteredRequest = array();

		// search by query string
		if (isset($request['query'])) {
			// we have a search query, so we use it
			$queryString = mysql_real_escape_string($request['query']);
			if (!empty($queryString)) {
				$demand['query'] = '%' . $queryString . '%';
				$filteredRequest['query'] = $queryString;
			}
		}

		// filter by national
		if (isset($request['kantons'])) {
			$demand['kantons'] = $request['kantons'];
			$filteredRequest['kantons'] = $request['kantons'];
		}

		// filter by national
		if (isset($request['national'])) {
			$demand['national'] = $request['national'];
			$filteredRequest['national'] = $request['national'];
		}

		// filter by years
		if (isset($request['years'])) {
			$demand['years'] = $request['years'];
			$filteredRequest['years'] = $request['years'];
		}

		$metaVotingProposals = $this->metaVotingProposalRepository->findByDemand($demand);

		$this->view->assign('metaVotingProposals', $metaVotingProposals);
		$this->view->assign('request', $filteredRequest);


	}

	/**
	 * action show
	 *
	 * @param \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal
	 * @return void
	 */
	public function showAction(\Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal) {
		$this->view->assign('metaVotingProposal', $metaVotingProposal);
	}

}
?>