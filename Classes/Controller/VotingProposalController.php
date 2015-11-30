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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class VotingProposalController extends AbstractController
{

    const VOTE_UP_VALUE = 1;
    const VOTE_DOWN_VALUE = 2;

    /**
     * @var \Visol\Easyvote\Domain\Repository\VotingProposalRepository
     * @inject
     */
    protected $votingProposalRepository;

    /**
     * @var \Visol\Easyvote\Domain\Repository\MetaVotingProposalRepository
     * @inject
     */
    protected $metaVotingProposalRepository;

    /**
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
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     * @return string
     */
    public function showPollForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal)
    {
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

        $loggedInUser = $this->communityUserService->getCommunityUser();
        if ($loggedInUser) {
            $pollUserStatus = $this->pollRepository->findByVotingProposalAndUser($votingProposal, $loggedInUser);
            if (count($pollUserStatus)) {
                $returnArray['voteValue'] = $pollUserStatus->getFirst()->getValue();
                if ($pollUserStatus->getFirst()->getValue() === VotingProposalController::VOTE_UP_VALUE) {
                    $returnArray['voteUpText'] = LocalizationUtility::translate('voting.alreadyVotedUndo', 'easyvote');
                    $returnArray['voteDownText'] = LocalizationUtility::translate('voting.alreadyVoted', 'easyvote');
                } else {
                    $returnArray['voteUpText'] = LocalizationUtility::translate('voting.alreadyVoted', 'easyvote');
                    $returnArray['voteDownText'] = LocalizationUtility::translate('voting.alreadyVotedUndo', 'easyvote');
                }
            } else {
                $returnArray['voteUpText'] = LocalizationUtility::translate('voting.voteUp', 'easyvote');
                $returnArray['voteDownText'] = LocalizationUtility::translate('voting.voteDown', 'easyvote');
                $returnArray['voteValue'] = 0;
            }
        } else {
            $returnArray['voteUpText'] = LocalizationUtility::translate('voting.notAuthenticated', 'easyvote');
            $returnArray['voteDownText'] = LocalizationUtility::translate('voting.notAuthenticated', 'easyvote');
        }

        return json_encode($returnArray);
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     * @return string
     */
    public function undoUserVoteForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal)
    {
        $loggedInUser = $this->communityUserService->getCommunityUser();
        if ($loggedInUser) {
            $activeVote = $this->pollRepository->findByVotingProposalAndUser($votingProposal, $loggedInUser)->getFirst();
            if ($activeVote instanceof \Visol\Easyvote\Domain\Model\Poll) {
                $this->pollRepository->remove($activeVote);
                $this->persistenceManager->persistAll();
                return json_encode(TRUE);
            }
        }
        return json_encode(FALSE);
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\VotingProposal $votingProposal
     * @param integer $value
     * @return string
     */
    public function voteForVotingProposalAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal, $value)
    {
        $loggedInUser = $this->communityUserService->getCommunityUser();
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
            $standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
            $standaloneView->setFormat('html');
            $standaloneView->setTemplatePathAndFilename('typo3conf/ext/easyvote/Resources/Private/Partials/VotingProposal/VotingAnswer.html');
            $standaloneView->assignMultiple(array(
                'metaVotingProposal' => $metaVotingProposal,
                'value' => $value,
                'voteUpValue' => VotingProposalController::VOTE_UP_VALUE,
                'votingProposal' => $votingProposal,
                'settings' => $this->settings,
            ));

            return json_encode(array('successText' => $standaloneView->render()));
        }
        return json_encode(array('successText' => '<p>Fehler.</p>'));
    }

    /**
     * Checks if the link requests a votingProposal and the votingProposal exists
     * Redirects to the current votings page if an error occurs
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function initializePermalinkAction()
    {
        if ($this->request->hasArgument('votingProposal')) {
            $votingProposalAndLanguage = GeneralUtility::trimExplode('-', $this->request->getArgument('votingProposal'));
            $this->request->setArgument('votingProposal', (int)$votingProposalAndLanguage[0]);
            $this->request->setArgument('language', (int)$votingProposalAndLanguage[1]);
            if (is_null($this->votingProposalRepository->findByUid((int)$votingProposalAndLanguage[0]))) {
                $targetUri = $this->uriBuilder->setTargetPageUid((int)$this->settings['currentVotingsPid'])->build();
                $this->redirectToUri($targetUri);
            }
        } else {
            $targetUri = $this->uriBuilder->setTargetPageUid((int)$this->settings['currentVotingsPid'])->build();
            $this->redirectToUri($targetUri);
        }
    }

    /**
     * Resolves a permalink to a votingProposal and opens it either in the archive or on the current votings page
     *
     * @param \Visol\Easyvote\Domain\Model\VotingProposal|NULL $votingProposal
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function permalinkAction(\Visol\Easyvote\Domain\Model\VotingProposal $votingProposal = NULL)
    {
        $metaVotingProposal = $this->metaVotingProposalRepository->findOneByVotingProposal($votingProposal);
        $language = $this->request->getArgument('language');
        if ($metaVotingProposal instanceof \Visol\Easyvote\Domain\Model\MetaVotingProposal) {
            if ($metaVotingProposal->getVotingDay()->isArchived()) {
                $redirectUri = $this->uriBuilder->setTargetPageUid((int)$this->settings['votingArchivePid'])->setArguments(array('tx_easyvote_archive' => array('selectSingle' => $votingProposal->getUid()), 'L' => $language))->build();
                $this->redirectToUri($redirectUri);
            } else {
                $redirectUri = $this->uriBuilder->setTargetPageUid((int)$this->settings['currentVotingsPid'])->setArguments(array('tx_easyvote_currentvotings' => array('selectSingle' => $votingProposal->getUid()), 'L' => $language))->build();
                $this->redirectToUri($redirectUri);
            }
        }
    }

}
