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
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AbstractController extends ActionController
{

    /**
     * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
     * @inject
     */
    protected $communityUserRepository;

    /**
     * @var \Visol\Easyvote\Service\CommunityUserService
     * @inject
     */
    protected $communityUserService;

    /**
     * TYPO3 user login
     * @param string $username
     * @return boolean
     */
    public function loginUser($username)
    {
        $loginData = array();
        $loginData['uname'] = $username;
        $loginData['status'] = 'login';

        $GLOBALS['TSFE']->fe_user->checkPid = 0;
        $info = $GLOBALS['TSFE']->fe_user->getAuthInfoArray();
        $storagePid = $this->settings['userStoragePid'];
        $info['db_user']['checkPidList'] = 1;
        $info['db_user']['check_pid_clause'] = 'AND pid IN(' . $storagePid . ')';
        $user = $GLOBALS['TSFE']->fe_user->fetchUserRecord($info['db_user'], $loginData['uname']);
        $GLOBALS['TSFE']->fe_user->createUserSession($user);
        $GLOBALS['TSFE']->fe_user->loginSessionStarted = TRUE;
        $GLOBALS['TSFE']->fe_user->user = $GLOBALS['TSFE']->fe_user->fetchUserSession();
    }

    /**
     * @param string $viewType One of Template, Partial, Layout
     * @param string $filename Filename of requested template/partial/layout, may be prefixed with subfolders
     * @return string
     */
    public function resolveViewFileForStandaloneView($viewType, $filename)
    {
        $extbaseConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK, 'easyvote', 'easyvote');
        if (array_key_exists(lcfirst($viewType) . 'RootPath', $extbaseConfiguration['view'])) {
            // deprecated singular setting
            return GeneralUtility::getFileAbsFileName($extbaseConfiguration['view']['templateRootPath'] . $filename);
        } else {
            // new setting, reverse array (because highest priority is last)
            $viewTypeConfigurationArray = array_reverse($extbaseConfiguration['view'][lcfirst($viewType) . 'RootPaths']);
            // check if the requested file exists at location and return the first file found
            foreach ($viewTypeConfigurationArray as $viewTypeConfiguration) {
                if (file_exists(GeneralUtility::getFileAbsFileName($viewTypeConfiguration . $filename))) {
                    return GeneralUtility::getFileAbsFileName($viewTypeConfiguration . $filename);
                }
            }
        }
    }

    /**
     * Debugs a SQL query from a QueryResult
     *
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult
     * @param boolean $explainOutput
     * @return void
     */
    public function debugQuery(\TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $queryResult, $explainOutput = FALSE)
    {
        $GLOBALS['TYPO3_DB']->debugOuput = 2;
        if ($explainOutput) {
            $GLOBALS['TYPO3_DB']->explainOutput = TRUE;
        }
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = TRUE;
        $queryResult->toArray();
        var_dump($GLOBALS['TYPO3_DB']->debug_lastBuiltQuery);
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = FALSE;
        $GLOBALS['TYPO3_DB']->explainOutput = FALSE;
        $GLOBALS['TYPO3_DB']->debugOuput = FALSE;
    }

    /**
     * Returns an instance of the Frontend object.
     *
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getFrontendObject()
    {
        return $GLOBALS['TSFE'];
    }

}
