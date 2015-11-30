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
 * Class PollRepository
 */
class PollRepository extends Repository
{

    /**
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     * @param $value integer
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function countByVotingProposalAndValue(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, $value)
    {
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
    public function findByVotingProposalAndUser(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, \Visol\Easyvote\Domain\Model\CommunityUser $communityUser)
    {
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