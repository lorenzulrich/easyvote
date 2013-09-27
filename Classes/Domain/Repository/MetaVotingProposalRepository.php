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
		$andConstraints = array();

		if (isset($demand['queryString'])) {
			$andConstraints = $query->logicalOr(
				$query->like('votingProposals.shortTitle', $demand['queryString']),
				$query->like('votingProposals.officialTitle', $demand['queryString']),
				$query->like('votingProposals.goal', $demand['queryString']),
				$query->like('votingProposals.initialStatus', $demand['queryString']),
				$query->like('votingProposals.consequence', $demand['queryString']),
				$query->like('votingProposals.proArguments', $demand['queryString']),
				$query->like('votingProposals.contraArguments', $demand['queryString']),
				$query->like('votingProposals.governmentOpinion', $demand['queryString']),
				$query->like('votingProposals.links', $demand['queryString'])
			);
		}

		$query->matching(
			$query->logicalAnd(
				$andConstraints
			)
		);


		return $query->execute();
	}
}
?>
