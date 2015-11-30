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

use Visol\Easyvote\Domain\Model\City;
use Visol\Easyvote\Domain\Model\CommunityUser;


/**
 * Controller
 */
class PartyMemberController extends CommunityUserController
{

    public function initializeAction()
    {
        $communityUser = $this->communityUserService->getCommunityUser();
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
    public function confirmAction(\Visol\Easyvote\Domain\Model\CommunityUser $object)
    {
        $communityUser = $this->communityUserService->getCommunityUser();
        if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
            $pendingPoliticianUsergroup = $this->communityUserService->getUserGroup('pendingPolitician');
            $object->removeUsergroup($pendingPoliticianUsergroup);
            $politicianUsergroup = $this->communityUserService->getUserGroup('politician');
            $object->addUsergroup($politicianUsergroup);
            $this->communityUserRepository->update($object);
            $this->persistenceManager->persistAll();

            // Send confirmation e-mail
            /** @var \Visol\Easyvote\Service\TemplateEmailService $templateEmail */
            $templateEmail = $this->objectManager->get('Visol\Easyvote\Service\TemplateEmailService');
            $templateEmail->addRecipient($object);
            $templateEmail->setTemplateName('partyMemberConfirm');
            $templateEmail->setExtensionName($this->request->getControllerExtensionName());
            $templateEmail->assign('communityUser', $object);
            $templateEmail->enqueue();

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
    public function declineAction(\Visol\Easyvote\Domain\Model\CommunityUser $object)
    {
        $communityUser = $this->communityUserService->getCommunityUser();
        if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
            $pendingPoliticianUsergroup = $this->communityUserService->getUserGroup('pendingPolitician');
            $object->removeUsergroup($pendingPoliticianUsergroup);
            $object->setParty(NULL);
            $this->communityUserRepository->update($object);
            $this->persistenceManager->persistAll();

            // Send confirmation e-mail
            /** @var \Visol\Easyvote\Service\TemplateEmailService $templateEmail */
            $templateEmail = $this->objectManager->get('Visol\Easyvote\Service\TemplateEmailService');
            $templateEmail->addRecipient($object);
            $templateEmail->setTemplateName('partyMemberDecline');
            $templateEmail->setExtensionName($this->request->getControllerExtensionName());
            $templateEmail->assign('communityUser', $object);
            $templateEmail->enqueue();

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
    public function removeAction(\Visol\Easyvote\Domain\Model\CommunityUser $object)
    {
        $communityUser = $this->communityUserService->getCommunityUser();
        if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
            $politician = $this->communityUserService->getUserGroup('politician');
            $object->removeUsergroup($politician);
            $object->setParty(NULL);
            $this->communityUserRepository->update($object);
            $this->persistenceManager->persistAll();

            // Send confirmation e-mail
            /** @var \Visol\Easyvote\Service\TemplateEmailService $templateEmail */
            $templateEmail = $this->objectManager->get('Visol\Easyvote\Service\TemplateEmailService');
            $templateEmail->addRecipient($object);
            $templateEmail->setTemplateName('partyMemberRemove');
            $templateEmail->setExtensionName($this->request->getControllerExtensionName());
            $templateEmail->assign('communityUser', $object);
            $templateEmail->enqueue();

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
    public function grantAdminAction(\Visol\Easyvote\Domain\Model\CommunityUser $object)
    {
        $communityUser = $this->communityUserService->getCommunityUser();
        if ($communityUser->getParty()->getUid() === $object->getParty()->getUid()) {
            $partyAdministratorGroup = $this->communityUserService->getUserGroup('partyAdministrator');
            $object->addUsergroup($partyAdministratorGroup);
            $this->communityUserRepository->update($object);
            $this->persistenceManager->persistAll();
            return json_encode(array('namespace' => 'Easyvote', 'function' => 'getPartyMembers', 'arguments' => $object->getUid()));
        } else {
            // TODO access denied - party administrator of another party
        }
    }

    /**
     * Returns members of the party of the currently authenticated party administrator
     * Optionally finds the members by their first name, last name and city through a given queryString
     * This method is called by a select2 search field
     *
     * @return string
     */
    public function getMembersOfCurrentPartyAction()
    {
        $communityUser = $this->communityUserService->getCommunityUser();
        if ($communityUser || $communityUser->isPartyAdministrator()) {
            // Party is a lazy property of CommunityUser
            if ($communityUser->getParty() instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
                $communityUser->getParty()->_loadRealInstance();
            }
            // query string
            $q = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('q');
            $usersTable = 'fe_users';
            $queryString = $GLOBALS['TYPO3_DB']->escapeStrForLike($GLOBALS['TYPO3_DB']->quoteStr($q, $usersTable), $usersTable);
            $partyMembers = $this->communityUserRepository->findPoliticiansByPartyAndQueryString($communityUser->getParty(), $queryString);
            $returnArray['results'] = array();
            foreach ($partyMembers as $partyMember) {
                /** @var $partyMember \Visol\Easyvote\Domain\Model\CommunityUser */
                $label = $partyMember->getCitySelection() instanceof City ?
                    $partyMember->getFirstName() . ' ' . $partyMember->getLastName() . ', ' . $partyMember->getCitySelection()->getName() :
                    $partyMember->getFirstName() . ' ' . $partyMember->getLastName();
                $returnArray['results'][] = array(
                    'id' => $partyMember->getUid(),
                    'text' => $label
                );
            }
            $returnArray['more'] = FALSE;
            return json_encode($returnArray);
        } else {
            // TODO access denied
        }
    }

}
