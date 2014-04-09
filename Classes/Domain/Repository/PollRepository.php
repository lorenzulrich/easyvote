<?php
namespace Visol\Easyvote\Domain\Repository;

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

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PollRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @param $value integer
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function countByVotingProposalAndValue(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, $value) {
		$query = $this->createQuery();

		$query->matching(
			$query->logicalAnd(
				$query->equals('votingProposal', $votingProposal),
				$query->equals('value', $value)
			)
		);

		return $query->execute()->count();
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
	 * @internal param int $value
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByVotingProposalAndUser(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, \Visol\Easyvote\Domain\Model\CommunityUser $communityUser) {
		$query = $this->createQuery();

		$query->matching(
			$query->logicalAnd(
				$query->equals('votingProposal', $votingProposal),
				$query->equals('communityUser', $communityUser)
			)
		);

		return $query->execute();
	}
}
?>