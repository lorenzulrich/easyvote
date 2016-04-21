<?php
namespace Visol\Easyvote\Command;

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

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * DataManagerCommandController
 */
class DataManagerCommandController extends CommandController
{

    /**
     * Export Data Manager Users
     */
    public function exportCommand()
    {

        $users = $this->getDatabaseConnection()->exec_SELECTgetRows('*', 'fe_users', 'tx_extbase_type = "Tx_EasyvoteImporter_BusinessUser" AND deleted = 0');

        $fileNameAndPath = '/tmp/data_manager_export_.json';
        file_put_contents($fileNameAndPath, json_encode($users));

        $this->outputLine('File exported at ' . $fileNameAndPath);
    }

    /**
     * Returns a pointer to the database.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}
