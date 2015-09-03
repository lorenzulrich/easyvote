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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class EventController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\PartyRepository
	 * @inject
	 */
	protected $partyRepository;

	/**
	 * @var \Visol\Easyvote\Service\CommunityUserService
	 * @inject
	 */
	protected $communityUserService;

	/**
	 * Access check
	 */
	public function initializeAction() {
		if (!$this->getLoggedInUser()) {
			$code = 401;
			$message = 'Authorization Required';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * action editMobilizations
	 */
	public function mobilizationsAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		$this->view->assign('communityUser', $communityUser);

		$shareUri = $this->uriBuilder->setTargetPageUid($this->settings['electionSupporterPid'])
			// TODO enable
			//->setAbsoluteUriScheme('https')
			->setUseCacheHash(FALSE)
			->setArguments(array('tx_easyvote_electionsupporterfunctions' => array('follow' => base64_encode($communityUser->getUid()))))
			->setCreateAbsoluteUri(TRUE)->build();

		$this->view->assign('encodedShareUri', urlencode($shareUri));
		$this->view->assign('shareUri', $shareUri);
	}

}