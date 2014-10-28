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
class VotingDayRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	protected $defaultOrderings = array(
		'votingDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
	);

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	public function findCurrentVotingDay() {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->greaterThan('votingDate', time()),
				$query->equals('archived', FALSE)
			)
		);
		$query->setOrderings(array(
			'votingDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		));
		$query->setLimit(1);
		return $query->execute()->getFirst();
	}
	
	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	public function findNextVotingDay() {
		$query = $this->createQuery();
		$query->statement('SELECT * FROM tx_easyvote_domain_model_votingday WHERE voting_date > ' . time() . ' AND deleted = 0 ORDER BY voting_date ASC LIMIT 1');
		return $query->execute()->getFirst();
	}

	/**
	 * Find an item that can also be hidden (because a voting day may not yet be online, but needed for upload)
	 *
	 * @param $uid
	 * @return \Visol\Easyvote\Domain\Model\VotingDay
	 */
	public function findVisibleAndHiddenByUid($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$object = $query->matching($query->equals('uid', $uid))->execute()->getFirst();
		return $object;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	public function findUploadAllowedVotingDays() {
		$query = $this->createQuery();
		$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
		$query->matching($query->equals('uploadAllowed', TRUE));
		return $query->execute();
	}

}
?>