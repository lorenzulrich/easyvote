<?php
namespace Visol\Easyvote\Domain\Repository;
use TYPO3\CMS\Backend\Utility\BackendUtility;

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

class PartyRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	protected $defaultOrderings = array(
		'short_title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		'title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
	);

	/**
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findYoungParties() {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->matching(
			$query->equals('isYoungParty', TRUE)
		);
		return $query->execute();
	}

	/**
	 * Find all with TYPO3 DatabaseConnection and return it as a raw array
	 *
	 * @return array|NULL
	 */
	public function findAllAsRawArray(){
		$tableName = 'tx_easyvote_domain_model_party';

		$clause = '1=1';
		// exclude party "Andere"
		$clause .= ' AND uid NOT IN (28)';
		$clause .= $this->getDeleteClauseAndEnableFieldsConstraint($tableName);
		$clause .= ' AND (sys_language_uid IN (-1,0) OR (sys_language_uid = ' . $GLOBALS['TSFE']->sys_language_uid. ' AND l10n_parent = 0))';

		$fields = ' *';
		$parties = $this->getDatabaseConnection()->exec_SELECTgetRows($fields, $tableName, $clause, '', 'title ASC');
		if (count($parties)) {
			foreach ($parties as $key => $row) {
				$parties[$key] = $this->getPageRepository()->getRecordOverlay($tableName, $row, $GLOBALS['TSFE']->sys_language_content, $GLOBALS['TSFE']->sys_language_contentOL);
			}
		}
		return $parties;
	}

	/**
	 * Build the enableFields constraint based on the current TYPO3 mode
	 * PageRepository is not available in CLI/Backend context
	 *
	 * @param $tableName
	 * @return string
	 */
	protected function getDeleteClauseAndEnableFieldsConstraint($tableName) {
		if (TYPO3_MODE === 'FE') {
			return $this->getPageRepository()->deleteClause($tableName) .
			$this->getPageRepository()->enableFields($tableName);
		} else {
			return BackendUtility::deleteClause($tableName);
		}
	}

	/**
	 * Returns a pointer to the database.
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Returns an instance of the page repository.
	 *
	 * @return \TYPO3\CMS\Frontend\Page\PageRepository
	 */
	protected function getPageRepository() {
		return $GLOBALS['TSFE']->sys_page;
	}

}

