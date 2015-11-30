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
 * Class VotingDayRepository
 */
class VotingDayRepository extends Repository
{

    protected $defaultOrderings = array(
        'votingDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
    );

    /**
     * @return \Visol\Easyvote\Domain\Model\VotingDay
     */
    public function findCurrentVotingDay()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('archived', FALSE)
        );
        $query->setOrderings(array(
            'votingDate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
        ));
        $query->setLimit(1);
        return $query->execute()->getFirst();
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\VotingDay
     */
    public function findNextVotingDay()
    {
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
    public function findVisibleAndHiddenByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);
        $object = $query->matching($query->equals('uid', $uid))->execute()->getFirst();
        return $object;
    }

    /**
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllVisibleAndHidden()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        return $query->execute();
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    public function findUploadAllowedVotingDays()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(TRUE);
        $query->matching($query->equals('uploadAllowed', TRUE));
        return $query->execute();
    }

}
