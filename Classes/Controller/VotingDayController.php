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
	 * action showCurrentVotingDay
	 *
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton requested Kanton
	 * @param \Visol\Easyvote\Domain\Model\VotingProposal $selectSingle
	 * @return void
	 */
	public function showCurrentVotingDayAction(\Visol\Easyvote\Domain\Model\Kanton $kanton = NULL, \Visol\Easyvote\Domain\Model\VotingProposal $selectSingle = NULL) {
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