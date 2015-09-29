<?php
namespace Visol\Easyvote\Domain\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
				if (array_key_exists('newsletters', $filterDemand)) {
					$newsletterConstraints = array();
					foreach ($filterDemand['newsletters'] as $newsletterFilterDemand) {
						$newsletterConstraints[] = $query->equals($newsletterFilterDemand, 1);
					}
					if (count($newsletterConstraints)) {
						$constraints[] = $query->logicalOr($newsletterConstraints);
					}
				}
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
				$queryString = '%' . $this->getDatabaseConnection()->escapeStrForLike($this->getDatabaseConnection()->quoteStr($demand['query'], $this->tableName), $this->tableName) . '%';
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
	 * @param \Visol\Easyvote\Domain\Model\CommunityUser|NULL $excludeSupporter
	 * @param null $limit
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findElectionSupporters($demand = NULL, $excludeSupporter = NULL, $limit = NULL) {
		$query = $this->createQuery();
		$constraints = [];

		$constraints[] = $query->logicalNot(
			$query->equals('events', 0)
		);

		// Make sure user is a Community user (exclude deleted users)
		$constraints[] = $query->contains('usergroup', $this->communityUserService->getUserGroupUid('community'));

		// Exclude a supporter if requested, this is used to exclude the election supporter's supporter because they
		// should be sorted first
		if ($excludeSupporter) {
			$constraints[] = $query->logicalNot(
				$query->equals('uid', $excludeSupporter->getUid())
			);
		}

		if (is_array($demand)) {
			if (isset($demand['query'])) {
				// query constraint
				$queryString = '%' . $this->getDatabaseConnection()->escapeStrForLike($this->getDatabaseConnection()->quoteStr($demand['query'], $this->tableName), $this->tableName) . '%';
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

			if (isset($demand['excludeUsersWithPrivacyProtection']) && $demand['excludeUsersWithPrivacyProtection']) {
				// privacyProtection
				$constraints[] = $query->equals('privacyProtection', FALSE);
			}

//			if (isset($demand['excludeUsersWithoutPicture']) && $demand['excludeUsersWithoutPicture']) {
//				// exclude users without picture
//				$constraints[] = $query->logicalOr(
//					$query->logicalNot($query->equals('falImage.uid', NULL)),
//					$query->contains('usergroup', $this->communityUserService->getUserGroupUid('communityFacebook'))
//				);
//			}
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

		// Set limit if requested
		if ($limit) {
			$query->setLimit($limit);
		}

		return $query->execute();

	}

	/**
	 * Find all election supporters that are either Facebook users or have an image
	 *
	 * @param null $limit
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findElectionSupportersForWall($limit = NULL) {
		$query = $this->createQuery();
		$q = 'SELECT * FROM fe_users
			WHERE NOT disable AND NOT deleted
			AND events > 0
			AND FIND_IN_SET(' . $this->communityUserService->getUserGroupUid('community') . ', usergroup) > 0
			AND NOT privacy_protection
			AND (fal_image > 0 OR FIND_IN_SET(' . $this->communityUserService->getUserGroupUid('communityFacebook') . ', usergroup) > 0)
			ORDER BY followers DESC, last_name ASC, first_name ASC
		';
		if ($limit) {
			$q .= ' LIMIT ' . (int)$limit;
		}
		$query->statement($q);
		return $query->execute();
	}

	/**
	 * Count method for the query above
	 *
	 * @return mixed
	 */
	public function countElectionSupportersForWall() {
		$query = $this->createQuery();
		$q = 'SELECT COUNT(*) as count FROM fe_users
			WHERE NOT disable AND NOT deleted
			AND events > 0
			AND FIND_IN_SET(' . $this->communityUserService->getUserGroupUid('community') . ', usergroup) > 0
		';
		$query->statement($q);
		return $query->execute(TRUE)[0]['count'];
	}

	/**
	 * Update the relations count for an 1:n IRRE relation
	 *
	 * @param string $foreignTable The table with child records
	 * @param string $foreignField The field in the child record holding the uid of the parent
	 * @param string $localRelationField The field that holds the relation count
	 * @param string $localTable The parent table
	 * @param array $localEnableFields The enable fields to consider for the parent table
	 * @param array $foreignEnableFields The enable fields to consider from the children table
	 */
	public function updateRelationCount($foreignTable, $foreignField, $localRelationField, $localTable = 'fe_users', $localEnableFields = array('hidden', 'deleted'), $foreignEnableFields = array('hidden', 'deleted')) {
		$foreignEnableFieldsClause = '';
		foreach ($foreignEnableFields as $foreignEnableField) {
			$foreignEnableFieldsClause .= ' AND NOT ' . $foreignEnableField;
		}
		$localEnableFieldsClause = '';
		foreach ($localEnableFields as $localEnableField) {
			$localEnableFieldsClause .= ' AND NOT parent.' . $localEnableField;
		}
		$q = '
			UPDATE ' . $localTable . ' AS parent
			LEFT JOIN (
				SELECT ' . $foreignField . ', COUNT(*) foreignCount
				FROM  ' . $foreignTable . '
				WHERE 1=1 ' . $foreignEnableFieldsClause . '
				GROUP BY ' . $foreignField . '
				) AS children
			ON parent.uid = children.' . $foreignField . '
			SET parent.' . $localRelationField . ' = CASE
				WHEN children.foreignCount IS NULL THEN 0
				WHEN children.foreignCount > 0 THEN children.foreignCount
			END
			WHERE 1=1 ' . $localEnableFieldsClause . ';
		';
		$this->getDatabaseConnection()->sql_query($q);
	}

	/**
	 * Update cached follower ranks for
	 * - Switzerland
	 * - Each canton
	 * - VIP rating
	 *
	 * @return void
	 */
	public function updateCachedFollowerRanks() {
		// Reset all ranks
		$sql = 'UPDATE fe_users SET cached_follower_rank_ch=0, cached_follower_rank_canton=0, cached_follower_rank_vip=0';
		$this->getDatabaseConnection()->sql_query($sql);

		/* Source: http://stackoverflow.com/a/14297055/1517316 */

		// Calculate rank for CH rating
		$this->getDatabaseConnection()->sql_query('SET @prev_value = NULL;');
		$this->getDatabaseConnection()->sql_query('SET @rank_count = 0;');
		$sql = '
			UPDATE fe_users
			SET cached_follower_rank_ch = CASE
				WHEN @prev_value = followers THEN @rank_count
				WHEN @prev_value := followers THEN @rank_count := @rank_count + 1
				ELSE @rank_count := @rank_count + 1
			END
			WHERE deleted = 0 AND disable = 0 AND followers > 0 AND events > 0 AND NOT vip
			ORDER BY followers DESC';
		$this->getDatabaseConnection()->sql_query($sql);

		// Calculate rank for VIP rating
		$this->getDatabaseConnection()->sql_query('SET @prev_value = NULL;');
		$this->getDatabaseConnection()->sql_query('SET @rank_count = 0;');
		$sql = '
			UPDATE fe_users
			SET cached_follower_rank_vip = CASE
				WHEN @prev_value = followers THEN @rank_count
				WHEN @prev_value := followers THEN @rank_count := @rank_count + 1
				ELSE @rank_count := @rank_count + 1
			END
			WHERE deleted = 0 AND disable = 0 AND followers > 0 AND events > 0 AND vip
			ORDER BY followers DESC';
		$this->getDatabaseConnection()->sql_query($sql);
		
		// Calculate rank for canton rating

		// set kanton for each user (TODO kanton field is deprecated, but this is the easiest way right now)
		$sql = '
			update fe_users as u
			set kanton = (select kanton from tx_easyvote_domain_model_city where uid = u.city_selection)
			where not u.deleted and not u.disable and u.followers > 0;';
		$this->getDatabaseConnection()->sql_query($sql);

		// get all affected cantons
		$sql = '
			SELECT DISTINCT kanton
			FROM fe_users
			WHERE NOT deleted AND NOT disable AND followers > 0 AND kanton > 0
			GROUP BY kanton;
		';
		$res = $this->getDatabaseConnection()->sql_query($sql);
		while ($row = $this->getDatabaseConnection()->sql_fetch_assoc($res)) {
			// set ranking for each canton
			$kantonUid = $row['kanton'];
			$this->getDatabaseConnection()->sql_query('SET @prev_value = NULL;');
			$this->getDatabaseConnection()->sql_query('SET @rank_count = 0;');
			$sql = '
			UPDATE fe_users
			SET cached_follower_rank_canton = CASE
				WHEN @prev_value = followers THEN @rank_count
				WHEN @prev_value := followers THEN @rank_count := @rank_count + 1
				ELSE @rank_count := @rank_count + 1
			END
			WHERE kanton =' . $kantonUid . '
			AND deleted = 0 AND disable = 0 AND followers > 0 AND events > 0 AND NOT vip
			ORDER BY followers DESC';
			$this->getDatabaseConnection()->sql_query($sql);
		}
	}

	/**
	 * Used only in ImportCommandController
	 *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findCommunityNewsRecipientsWithoutEvent() {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('communityNewsMailActive', 1),
				$query->equals('events', 0)
			)
		);
		return $query->execute();
	}

	/**
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	public function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}
