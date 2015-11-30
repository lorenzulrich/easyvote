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
 * Class MetaVotingProposalRepository
 */
class MetaVotingProposalRepository extends Repository
{
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    );

    /**
     * @param array $demand
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByDemand($demand)
    {
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

    /**
     * Get a query result containing just one requested object
     * This is necessary because the archive works with a query result, but a single record must be selectable
     *
     * @param $metaVotingProposalUid
     * @return object
     */
    public function getQueryResultByUid($metaVotingProposalUid)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('uid', $metaVotingProposalUid),
                $query->equals('votingDay.archived', TRUE)
            )
        );
        return $query->execute();
    }

    /**
     * Find the metaVotingProposal of a votingProposal
     *
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     * @return object
     */
    public function findOneByVotingProposal(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('votingProposals', $votingProposal->getUid())
        );
        return $query->execute()->getFirst();
    }

}
