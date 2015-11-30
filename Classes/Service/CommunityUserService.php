<?php
namespace Visol\Easyvote\Service;

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

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class CommunityUserService
 */
class CommunityUserService implements SingletonInterface
{

    /**
     * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
     * @inject
     */
    protected $communityUserRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
     * @inject
     */
    protected $frontendUserGroupRepository = NULL;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * Check if a user has a role as defined in EXT:easyvote settings with a specific syntax
     *
     * @param \Visol\Easyvote\Domain\Model\CommunityUser $communityUser
     * @param $role
     * @return bool
     */
    public function hasRole(\Visol\Easyvote\Domain\Model\CommunityUser $communityUser, $role)
    {
        $this->settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'easyvote', 'easyvote');
        $hasRole = FALSE;
        $roleUid = (int)$this->settings[$role . 'UserGroupUid'];
        if ($roleUid > 0) {
            foreach ($communityUser->getUsergroup() as $usergroup) {
                /* @var $usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
                if ($usergroup->getUid() === $roleUid) {
                    $hasRole = TRUE;
                    break;
                }
            }
        }
        return $hasRole;
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\CommunityUser|bool
     */
    public function getCommunityUser()
    {
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

    /**
     * Get a user group defined in EXT:easyvote TypoScript settings with a pre-defined syntax [name]UserGroupUid
     *
     * @param string $role
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
     */
    public function getUserGroup($role)
    {
        $this->settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'easyvote', 'easyvote');
        $roleUid = (int)$this->settings[$role . 'UserGroupUid'];
        return $this->frontendUserGroupRepository->findByUid($roleUid);
    }

    /**
     * Get a user group uid defined in EXT:easyvote TypoScript settings with a pre-defined syntax [name]UserGroupUid
     *
     * @param string $role
     * @return integer
     */
    public function getUserGroupUid($role)
    {
        $this->settings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'easyvote', 'easyvote');
        return (int)$this->settings[$role . 'UserGroupUid'];
    }

}
