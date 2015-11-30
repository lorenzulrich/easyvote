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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class PartyRepository
 * @package Visol\Easyvote\Domain\Repository
 */
class PartyRepository extends Repository
{

    protected $defaultOrderings = array(
        'short_title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        'title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    );

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findYoungParties()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching(
            $query->equals('isYoungParty', TRUE)
        );
        return $query->execute();
    }

    /**
     * Find all parties, exclude party "Andere"
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllWithoutOthers()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);
        $query->matching(
            $query->logicalNot(
            // Party 'Andere'
                $query->logicalOr(
                    $query->equals('uid', 28),
                    $query->equals('uid', 44),
                    $query->equals('uid', 45)
                )
            )
        );
        return $query->execute();
    }

    /**
     * Build the enableFields constraint based on the current TYPO3 mode
     * PageRepository is not available in CLI/Backend context
     *
     * @param $tableName
     * @return string
     */
    protected function getDeleteClauseAndEnableFieldsConstraint($tableName)
    {
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
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

    /**
     * Returns an instance of the page repository.
     *
     * @return \TYPO3\CMS\Frontend\Page\PageRepository
     */
    protected function getPageRepository()
    {
        return $GLOBALS['TSFE']->sys_page;
    }

}

