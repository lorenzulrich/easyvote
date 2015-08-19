<?php
namespace Visol\Easyvote\Domain\Repository;

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

class CommunityUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {

	protected $defaultOrderings = array(
		'lastName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		'firstName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
	);

	/**
	 * Name of the table for this repository
	 *
	 * @var string
	 */
	protected $tableName = 'fe_users';

	/**
	 * @var \Visol\Easyvote\Service\CommunityUserService
	 * @inject
	 */
	protected $communityUserService = NULL;

	/**
	 * @param int $uid
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser|NULL
	 */
	public function findHiddenByUid($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
		$query->matching(
			$query->equals('uid', (int)$uid)
		);
		return $query->execute()->getFirst();
	}

	/**
	 * findByUsername function not respecting enableFields
	 *
	 * @param string $username
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUsername($username) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setIgnoreEnableFields(TRUE);
		$query->matching(
			$query->equals('username', (string)$username)
		);
		return $query->execute();
	}

	/**
	 * Query users by kanton, language and jobtype
	 * Used for sending SMS messages and exporting e-mail addresses for the Votewecker
	 *
	 * @param $filterDemand
	 * @return array|bool|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByFilterDemand($filterDemand) {
		$query = $this->createQuery();

		$constraints = array();

		if (array_key_exists('type', $filterDemand)) {
			if ($filterDemand['type'] === \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_SMS) {
				$constraints[] = $query->equals('notificationSmsActive', 1);
			}
			if ($filterDemand['type'] === \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_EMAIL) {
				$constraints[] = $query->equals('notificationMailActive', 1);
			}
		}

		if (array_key_exists('userLanguages', $filterDemand)) {
			$userLanguages = array();
			foreach ($filterDemand['userLanguages'] as $userLanguage) {
				$userLanguages[] = $query->equals('userLanguage', $userLanguage);
			}
			$constraints[] = $query->logicalOr($userLanguages);
		}

		if (array_key_exists('kantons', $filterDemand)) {
			$useKantonsConstraint = TRUE;

			foreach ($filterDemand['kantons'] as $kanton) {
				if ((string)$kanton === 'all') {
					$useKantonsConstraint = FALSE;
				}
			}

			if ($useKantonsConstraint) {
				$kantons = array();
				foreach ($filterDemand['kantons'] as $kanton) {
					$kantons [] = $query->equals('kanton', $kanton);
				}
				$constraints[] = $query->logicalOr($kantons);
			}
		}

		if (count($constraints)) {
			return $query->matching(
				$query->logicalAnd($constraints)
			)->execute();
		} else {
			return FALSE;
		}
	}

	/**
	 * Party administrators can filter their members by query, kanton or status
	 *
	 * @param \Visol\Easyvote\Domain\Model\Party $party
	 * @param array|NULL $demand
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findPoliticiansByPartyAndDemand(\Visol\Easyvote\Domain\Model\Party $party, $demand) {
		$query = $this->createQuery();
		$constraints = [];
		$constraints[] = $query->equals('party', $party);

		if (is_array($demand)) {
			if (isset($demand['query'])) {
				// query constraint
				$queryString = '%' . $GLOBALS['TYPO3_DB']->escapeStrForLike($GLOBALS['TYPO3_DB']->quoteStr($demand['query'], $this->tableName), $this->tableName) . '%';
				$constraints[] = $query->logicalOr(
					$query->like('firstName', $queryString, FALSE),
					$query->like('lastName', $queryString, FALSE),
					$query->like('citySelection.name', $queryString, FALSE)
				);
			}

			if (isset($demand['kanton']) && (int)$demand['kanton'] > 0) {
				// kanton constraint
				$constraints[] = $query->equals('citySelection.kanton', (int)$demand['kanton']);
			}

			if (isset($demand['status']) && in_array($demand['status'], array('politician', 'pendingPolitician'))) {
				// politician or pending politician constraint
				$constraints[] = $query->contains('usergroup', $this->communityUserService->getUserGroupUid($demand['status']));
			}

		}

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		return $query->execute();
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\Party $party
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findPendingPoliticiansByParty(\Visol\Easyvote\Domain\Model\Party $party) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('party', $party),
				$query->contains('usergroup', $this->communityUserService->getUserGroupUid('pendingPolitician'))
			)
		);
		return $query->execute();
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\Party $party
	 * @param string $queryString
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findPoliticiansByPartyAndQueryString(\Visol\Easyvote\Domain\Model\Party $party, $queryString) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('party', $party),
				$query->contains('usergroup', $this->communityUserService->getUserGroupUid('politician')),
				$query->logicalOr(
					$query->like('firstName', $queryString . '%', FALSE),
					$query->like('lastName', $queryString . '%', FALSE),
					$query->like('citySelection.name', $queryString . '%', FALSE)
				)
			)
		);
		return $query->execute();
	}

	/**
	 * Finds all administrators of a certain party
	 *
	 * @param \Visol\Easyvote\Domain\Model\Party $party
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findPartyAdministrators(\Visol\Easyvote\Domain\Model\Party $party) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('party', $party),
				$query->contains('usergroup', $this->communityUserService->getUserGroupUid('partyAdministrator'))
			)
		);
		return $query->execute();
	}

	/**
	 * Find all or filtered election supporters
	 *
	 * @param array|NULL $demand
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findElectionSupporters($demand = NULL) {
		$query = $this->createQuery();
		$constraints = [];
		// TODO make sure relation field is updated
		// Only select users with followers
		$constraints[] = $query->logicalNot(
			$query->equals('followers', 0)
		);
		// Make sure user is a Community user (exclude deleted users)
		$constraints[] = $query->contains('usergroup', $this->communityUserService->getUserGroupUid('community'));

		if (is_array($demand)) {
			if (isset($demand['query'])) {
				// query constraint
				$queryString = '%' . $GLOBALS['TYPO3_DB']->escapeStrForLike($GLOBALS['TYPO3_DB']->quoteStr($demand['query'], $this->tableName), $this->tableName) . '%';
				$constraints[] = $query->like('firstName', $queryString, FALSE);
			}

			if (isset($demand['city']) && (int)$demand['city'] > 0) {
				// city constraint
				$constraints[] = $query->equals('citySelection', (int)$demand['city']);
			}

			if (isset($demand['kanton']) && (int)$demand['kanton'] > 0) {
				// kanton constraint
				$constraints[] = $query->equals('citySelection.kanton', (int)$demand['kanton']);
			}
		}

		if (!empty($constraints)) {
			$query->matching(
				$query->logicalAnd($constraints)
			);
		}

		// Sort by number of followers
		$query->setOrderings(array(
			'followers' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
			'lastName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
			'firstName' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		));

		return $query->execute();

	}

}
