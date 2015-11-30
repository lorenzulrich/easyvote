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

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class MessagingJobRepository
 */
class MessagingJobRepository extends Repository
{

    /**
     * @param $jobType
     * @param integer $limit
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findPendingJobs($jobType, $limit)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(FALSE);

        $query->matching(
            $query->logicalAnd(
                $query->equals('type', $jobType),
                $query->lessThanOrEqual('distributionTime', \time()),
                $query->equals('timeDistributed', 0),
                $query->equals('timeError', 0)
            )
        );

        $query->setLimit((int)$limit);

        return $query->execute();
    }

}