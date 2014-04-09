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
class VotingProposalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var int
	 */
	protected $voteUpValue = 1;

	/**
	 * @var int
	 */
	protected $voteDownValue = 2;

	/**
	 * votingProposalRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\VotingProposalRepository
	 * @inject
	 */
	protected $votingProposalRepository;

	/**
	 * pollRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\PollRepository
	 * @inject
	 */
	protected $pollRepository;

	/**
	 * communityUserRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
	 * @inject
	 */
	protected $communityUserRepository;

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
		$upVotes = $this->pollRepository->countByVotingProposalAndValue($votingProposal, $this->voteUpValue);
		$downVotes = $this->pollRepository->countByVotingProposalAndValue($votingProposal, $this->voteDownValue);

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
				if ($pollUserStatus->getFirst()->getValue() === $this->voteUpValue) {
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

			$votingProposalUri = $this->uriBuilder->setTargetPageUid($this->settings['votingPid'])->setCreateAbsoluteUri(TRUE)->build();
			if ((int)$value === $this->voteUpValue) {
				$shareText = 'Ich sage JA zu "' . $votingProposal->getShortTitle() . '"! ' . $votingProposalUri;
			} else {
				$shareText = 'Ich sage NEIN zu "' . $votingProposal->getShortTitle() . '"! ' . $votingProposalUri;
			}

			$facebookShare = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' .$votingProposalUri . '"><i title="Meine Meinung auf Facebook teilen." class="flashMessageIcon icon icon-facebook" /></a>';
			$twitterShare = '<a target="_blank" href="https://twitter.com/home?status=' . htmlentities($shareText) . '"><i title="Meine Meinung auf Twitter teilen." class="flashMessageIcon icon icon-twitter" /></a>';

			return json_encode(array('successText' => '<p>Danke für deine Stimme! Teile deine Meinung:</p>' . $facebookShare . $twitterShare));
		}
		return json_encode(array('successText' => '<p>Fehler.</p>'));
	}

	/**
	 * @return \Visol\Easyvote\Domain\Model\CommunityUser|bool
	 */
	protected function getLoggedInUser() {
		if ((int)$GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
			$communityUser = $this->communityUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
			if ($communityUser instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
				return $communityUser;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

}
?>