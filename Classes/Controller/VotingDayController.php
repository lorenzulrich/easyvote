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

use Visol\Easyvote\Domain\Model\VotingProposal;

class VotingDayController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\VotingDayRepository
	 * @inject
	 */
	protected $votingDayRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\MetaVotingProposalRepository
	 * @inject
	 */
	protected $metaVotingProposalRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\VotingProposalRepository
	 * @inject
	 */
	protected $votingProposalRepository;

	/**
	 * Backwards compatibility for old permalinks:
	 * Before having a proper solution for permalinks, links always pointed to the current votings. As soon as the VotingDay
	 * was archived, the permalink wouldn't work anymore. This method checks if such a permalink was called and redirects
	 * to the archive if the VotingProposal requested was archived in the meantime.
	 *
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 */
	public function initializeShowCurrentVotingDayAction() {
		if ($this->request->hasArgument('selectSingle')) {
			$votingProposal = $this->votingProposalRepository->findByUid((int)$this->request->getArgument('selectSingle'));
			if ($votingProposal instanceof VotingProposal) {
				$metaVotingProposal = $this->metaVotingProposalRepository->findOneByVotingProposal($votingProposal);
				if ($metaVotingProposal instanceof \Visol\Easyvote\Domain\Model\MetaVotingProposal) {
					if ($metaVotingProposal->getVotingDay()->isArchived()) {
						$redirectUri = $this->uriBuilder->setTargetPageUid((int)$this->settings['votingArchivePid'])->setArguments(array('tx_easyvote_archive' => array('selectSingle' => $votingProposal->getUid())))->build();
						$this->redirectToUri($redirectUri);
					}
				}
			}
		}
	}

	/**
	 * action showCurrentVotingDay
	 *
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton requested Kanton
	 * @param VotingProposal $selectSingle
	 * @return void
	 */
	public function showCurrentVotingDayAction(\Visol\Easyvote\Domain\Model\Kanton $kanton = NULL, VotingProposal $selectSingle = NULL) {
		$votingDay = $this->votingDayRepository->findCurrentVotingDay();
		// view can be set via TypoScript
		$this->view->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->settings['votingDayViewTemplate']));
		$this->view->assignMultiple(array(
			'votingDay' => $votingDay,
			'requestedKanton' => $kanton
		));

		if ($selectSingle instanceof VotingProposal) {
			$this->view->assign('requestedVotingProposal', $selectSingle);
		}

	}

}
?>