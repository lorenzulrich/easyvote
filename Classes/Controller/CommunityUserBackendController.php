<?php
namespace Visol\Easyvote\Controller;

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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Visol\Easyvote\Domain\Model\CommunityUser;

class CommunityUserBackendController extends ActionController
{

	/**
	 * @var \Visol\Easyvote\Domain\Repository\PollRepository
	 * @inject
	 */
	protected $pollRepository;

    /**
     * @var \Visol\EasyvoteEducation\Domain\Repository\PanelInvitationRepository
     * @inject
     */
    protected $polittalkPanelInvitationRepository;

    /**
     * @var \Visol\EasyvoteEducation\Domain\Repository\PanelRepository
     * @inject
     */
    protected $polittalkPanelRepository;

	/**
	 * @var \Visol\EasyvoteCompetition\Domain\Repository\ParticipationRepository
	 * @inject
	 */
	protected $competitionParticipationRepository;

	/**
	 * @var \Visol\EasyvoteCompetition\Domain\Repository\VoteRepository
	 * @inject
	 */
	protected $competitionVotesRepository;

	/**
	 * @var \Visol\Votable\Domain\Repository\VotedObjectRepository
	 * @inject
	 */
	protected $votableVotedObjectRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

	/**
	 * @param CommunityUser $communityUser
	 */
	public function personalDataRequestAction(CommunityUser $communityUser) {
		$this->view->assign('communityUser', $communityUser);
		$this->view->assign('opinionsOnVotingProposals', $this->pollRepository->findByCommunityUser($communityUser));
		$this->view->assign('attendingPanelInvitations', $this->polittalkPanelInvitationRepository->findByAttendingCommunityUser($communityUser, false));
		$this->view->assign('organizedPanels', $this->polittalkPanelRepository->findByCommunityUser($communityUser, false));
		$this->view->assign('competitionParticipations', $this->competitionParticipationRepository->findByCommunityUser($communityUser, false));
		$this->view->assign('competitionVotes', $this->competitionVotesRepository->findByCommunityUser($communityUser, false));
		// TODO implement
		//$this->view->assign('smartvoteCandidateVotes', $this->votableVotedObjectRepository->findForContentTypeAndUserIdentifierResolvingVotedObject('tx_easyvotesmartvote_domain_model_candidate', $communityUser->getUid()));
		// TODO implement Spider Chart?
	}

}
