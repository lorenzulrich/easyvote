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
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Visol\Easyvote\Domain\Model\City;
use Visol\Easyvote\Domain\Model\CommunityUser;
use Visol\Easyvote\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PartyMemberController extends \Visol\Easyvote\Controller\CommunityUserController {

	/**
	 * @return \Visol\Easyvote\Service\CommunityUserService
	 */
	public function getCommunityUserService() {
		return $this->objectManager->get('Visol\Easyvote\Service\CommunityUserService');
	}

	public function initializeAction() {
		$communityUser = $this->getCommunityUserService()->getCommunityUser();
		if (!$communityUser || !$communityUser->isPartyAdministrator()) {
			$code = 401;
			$message = 'Access Denied';
			$this->response->setStatus($code, $message);
			$this->response->shutdown();
			die($message);
		}
	}

	/**
	 * Confirm a Community User as politician of a party
	 * Removes the pendingPolitician role and adds the politician role
	 *
	 * @param CommunityUser $object
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @return string
	 */
	public function confirmAction(\Visol\Easyvote\Domain\Model\CommunityUser $object) {
		$communityUser = $this->getCommunityUserService()->getCommunityUser();
		if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
			$pendingPoliticianUsergroup = $this->getCommunityUserService()->getUserGroup('pendingPolitician');
			$object->removeUsergroup($pendingPoliticianUsergroup);
			$politicianUsergroup = $this->getCommunityUserService()->getUserGroup('politician');
			$object->addUsergroup($politicianUsergroup);
			$this->communityUserRepository->update($object);
			$this->persistenceManager->persistAll();
			// TODO notify user
			return json_encode(array('namespace' => 'Easyvote', 'function' => 'getPartyMembers', 'arguments' => $object->getUid()));
		} else {
			// TODO access denied - party administrator of another party
		}
	}

	/**
	 * Decline a Community User as politician of a party
	 * Removes the pendingPolitician role and removes the party
	 *
	 * @param CommunityUser $object
	 * @return string
	 */
	public function declineAction(\Visol\Easyvote\Domain\Model\CommunityUser $object) {
		$communityUser = $this->getCommunityUserService()->getCommunityUser();
		if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
			$pendingPoliticianUsergroup = $this->getCommunityUserService()->getUserGroup('pendingPolitician');
			$object->removeUsergroup($pendingPoliticianUsergroup);
			$object->setParty(NULL);
			$this->communityUserRepository->update($object);
			$this->persistenceManager->persistAll();
			return json_encode(array('namespace' => 'Easyvote', 'function' => 'getPartyMembers', 'arguments' => null));
		} else {
			// TODO access denied - party administrator of another party
		}
	}

	/**
	 * Remove a Community User as politician of a party
	 * Removes the politician role and removes the party
	 *
	 * @param CommunityUser $object
	 * @return string
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function removeAction(\Visol\Easyvote\Domain\Model\CommunityUser $object) {
		$communityUser = $this->getCommunityUserService()->getCommunityUser();
		if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
			$politician = $this->getCommunityUserService()->getUserGroup('politician');
			$object->removeUsergroup($politician);
			$object->setParty(NULL);
			$this->communityUserRepository->update($object);
			$this->persistenceManager->persistAll();
			return json_encode(array('namespace' => 'Easyvote', 'function' => 'getPartyMembers', 'arguments' => null));
		} else {
			// TODO access denied - party administrator of another party
		}
	}

	/**
	 * Grants admin access to a politician
	 * Adds the party admin role
	 *
	 * @param CommunityUser $object
	 * @return string
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function grantAdminAction(\Visol\Easyvote\Domain\Model\CommunityUser $object) {
		$communityUser = $this->getCommunityUserService()->getCommunityUser();
		if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
			$partyAdministratorGroup = $this->getCommunityUserService()->getUserGroup('partyAdministrator');
			$object->addUsergroup($partyAdministratorGroup);
			$this->communityUserRepository->update($object);
			$this->persistenceManager->persistAll();
			return json_encode(array('namespace' => 'Easyvote', 'function' => 'getPartyMembers', 'arguments' => $object->getUid()));
		} else {
			// TODO access denied - party administrator of another party
		}
	}

}
