<?php
namespace Visol\Easyvote\Domain\Repository;

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
class MetaVotingProposalRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	protected $defaultOrderings = array(
		'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
	);

	/**
	 * @param $demand
	 * @return Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function findByDemand($demand) {
		$query = $this->createQuery();
		// TODO doesn't work with localization
		//$query->setOrderings(array('votingDay.votingDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		$constraints[] = array();

		// query string constraint
		if (isset($demand['query'])) {
			if (!empty($demand['query'])) {
				// we query all languages
				// TODO no effect
				//$query->getQuerySettings()->setRespectSysLanguage(FALSE);
				// query all text fields non-case sensitive
				$constraints[] = $query->logicalOr(
					$query->like('votingProposals.shortTitle', $demand['query'], FALSE),
					$query->like('votingProposals.officialTitle', $demand['query'], FALSE),
					$query->like('votingProposals.goal', $demand['query'], FALSE),
					$query->like('votingProposals.initialStatus', $demand['query'], FALSE),
					$query->like('votingProposals.consequence', $demand['query'], FALSE),
					$query->like('votingProposals.proArguments', $demand['query'], FALSE),
					$query->like('votingProposals.contraArguments', $demand['query'], FALSE),
					$query->like('votingProposals.governmentOpinion', $demand['query'], FALSE),
					$query->like('votingProposals.links', $demand['query'], FALSE)
				);
			}
		}

		// kantons constraint
		$kantonUids = array();
		if (is_array($demand['kantons'])) {
			// we query multiple kantons
			foreach ($demand['kantons'] as $kanton) {
				$kantonUids[] = $kanton;
			}
			$kantonsConstraint = $query->in('kanton', $kantonUids);
		}

		// national constraint
		if ($demand['national'] == 1) {
			$nationalConstraint = $query->equals('kanton', 0);
		}

		// combine these to scope constraint
		if (isset($kantonsConstraint)) {
			if (isset($nationalConstraint)) {
				$constraints[] = $query->logicalOr(
					$kantonsConstraint,
					$nationalConstraint
				);
			} else {
				$constraints[] = $kantonsConstraint;
			}
		} else {
			if (isset($nationalConstraint)) {
				$constraints[] = $nationalConstraint;
			}
		}

		// year constraint
		if (is_array($demand['years'])) {
			$years = array();
			foreach ($demand['years'] as $year) {
				$firstDayInYearTimeStamp = strtotime('1 January ' . $year);
				$lastDayInYearTimeStamp = strtotime('31 December ' . $year);
				$years[] = $query->logicalAnd(
					$query->greaterThanOrEqual('votingDay.votingDate', $firstDayInYearTimeStamp),
					$query->lessThanOrEqual('votingDay.votingDate', $lastDayInYearTimeStamp)
				);
			}
			$constraints[] = $query->logicalOr($years);
		}

		// remove empty constraints to prevent an exception
		foreach ($constraints as $key => $value) {
			if (empty($value)) {
				unset($constraints[$key]);
			}
		}

		// votingDay must be archived
		$constraints[] = $query->equals('votingDay.archived', 1);

		// build query from constraints
		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		return $query->execute();
	}
}
?>
