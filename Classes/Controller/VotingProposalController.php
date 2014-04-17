<?php
namespace Visol\Easyvote\Controller;

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
class VotingProposalController extends \Visol\Easyvote\Controller\AbstractController {

	const VOTE_UP_VALUE = 1;
	const VOTE_DOWN_VALUE = 2;

	/**
	 * votingProposalRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\VotingProposalRepository
	 * @inject
	 */
	protected $votingProposalRepository;

	/**
	 * metaVotingProposalRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\MetaVotingProposalRepository
	 * @inject
	 */
	protected $metaVotingProposalRepository;

	/**
	 * pollRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\PollRepository
	 * @inject
	 */
	protected $pollRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$votingProposals = $this->votingProposalRepository->findAll();
		$this->view->assign('votingProposals', $votingProposals);
	}

	/**
	 * action show
	 *
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @return void
	 */
	public function showAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal) {
		$this->view->assign('votingProposal', $votingProposal);
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @return string
	 */
	public function showPollForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal) {
		$upVotes = $this->pollRepository->countByVotingProposalAndValue($votingProposal, VotingProposalController::VOTE_UP_VALUE);
		$downVotes = $this->pollRepository->countByVotingProposalAndValue($votingProposal, VotingProposalController::VOTE_DOWN_VALUE);

		$totalVotes = $upVotes + $downVotes;

		if ($totalVotes > 0) {
			$upVoteRatio = round(($upVotes / $totalVotes) * 100);
			$downVoteRatio = 100 - $upVoteRatio;
		} else {
			$upVoteRatio = 0;
			$downVoteRatio = 0;
		}

		$returnArray = array(
			'results' => array(
				$upVoteRatio,
				$downVoteRatio
			)
		);

		$returnArray['user'] = '';

		$loggedInUser = $this->getLoggedInUser();
		if ($loggedInUser) {
			$pollUserStatus = $this->pollRepository->findByVotingProposalAndUser($votingProposal, $loggedInUser);
			if (count($pollUserStatus)) {
				$returnArray['voteValue'] = $pollUserStatus->getFirst()->getValue();
				if ($pollUserStatus->getFirst()->getValue() === VotingProposalController::VOTE_UP_VALUE) {
					$returnArray['voteUpText'] = 'Du hast bereits abgestimmt. Klicke, um die Stimme rückgängig zu machen.';
					$returnArray['voteDownText'] = 'Du hast bereits abgestimmt.';
				} else {
					$returnArray['voteUpText'] = 'Du hast bereits abgestimmt.';
					$returnArray['voteDownText'] = 'Du hast bereits abgestimmt. Klicke, um die Stimme rückgängig zu machen.';
				}
			} else {
				$returnArray['voteUpText'] = 'Stimme für diese Vorlage.';
				$returnArray['voteDownText'] = 'Stimme gegen diese Vorlage.';
			}
		} else {
			//<f:link.page pageUid="{settings.loginUrl}">Melde dich an</f:link.page>, um abzustimmen!
			$returnArray['voteUpText'] = 'Melde dich an, um abzustimmen';
			$returnArray['voteDownText'] = 'Melde dich an, um abzustimmen';
		}

		return json_encode($returnArray);
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @return string
	 */
	public function undoUserVoteForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal) {
		$loggedInUser = $this->getLoggedInUser();
		if ($loggedInUser) {
			$activeVote = $this->pollRepository->findByVotingProposalAndUser($votingProposal, $loggedInUser)->getFirst();
			$this->pollRepository->remove($activeVote);
			$this->persistenceManager->persistAll();
			return json_encode(TRUE);
		}
		return json_encode(FALSE);
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
	 * @param $value int
	 * @return string
	 */
	public function voteForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, $value) {
		$loggedInUser = $this->getLoggedInUser();
		$pollUserStatus = $this->pollRepository->findByVotingProposalAndUser($votingProposal, $loggedInUser)->count();
		if ($loggedInUser && $pollUserStatus === 0) {
			$newVote = new \Visol\Easyvote\Domain\Model\Poll();
			$newVote->setVotingProposal($votingProposal);
			$newVote->setCommunityUser($loggedInUser);
			$newVote->setValue((int)$value);
			$this->pollRepository->add($newVote);
			$this->persistenceManager->persistAll();

			/** @var \Visol\Easyvote\Domain\Model\MetaVotingProposal $metaVotingProposal */
			$metaVotingProposal = $this->metaVotingProposalRepository->findOneByVotingProposal($votingProposal);

			/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
			$standaloneView = $this->objectManager->create('TYPO3\CMS\Fluid\View\StandaloneView');
			$standaloneView->setFormat('html');
			$standaloneView->setTemplatePathAndFilename('typo3conf/ext/easyvote/Resources/Private/Partials/VotingProposal/VotingAnswer.html');
			$standaloneView->assignMultiple(array(
				'metaVotingProposal' => $metaVotingProposal,
				'value' => $value,
				'voteUpValue' => VotingProposalController::VOTE_UP_VALUE,
				'votingProposal' => $votingProposal
			));

			return json_encode(array('successText' => $standaloneView->render()));
		}
		return json_encode(array('successText' => '<p>Fehler.</p>'));
	}

}
?>