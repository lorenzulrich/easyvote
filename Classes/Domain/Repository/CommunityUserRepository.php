<?php
namespace Visol\Easyvote\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
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
class CommunityUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {

	public function findByFilterDemand($filterDemand) {
		$query = $this->createQuery();

		$constraints = array();

		if (array_key_exists('type', $filterDemand)) {
			if ($filterDemand['type'] === \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_SMS) {
				$constraints[] = $query->equals('notificationSmsActive', 1);
			}
			if ($filterDemand['type'] === \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_EMAIL) {
				$constraints[] = $query->equals('notificationMailActive', 1);
			}
		}

		if (array_key_exists('userLanguages', $filterDemand)) {
			$userLanguages = array();
			foreach ($filterDemand['userLanguages'] as $userLanguage) {
				$userLanguages[] = $query->equals('userLanguage', $userLanguage);
			}
			$constraints[] = $query->logicalOr($userLanguages);
		}

		if (array_key_exists('kantons', $filterDemand)) {
			$useKantonsConstraint = TRUE;

			foreach ($filterDemand['kantons'] as $kanton) {
				if ((string)$kanton === 'all') {
					$useKantonsConstraint = FALSE;
				}
			}

			if ($useKantonsConstraint) {
				$kantons = array();
				foreach ($filterDemand['kantons'] as $kanton) {
					$kantons [] = $query->equals('kanton', $kanton);
				}
				$constraints[] = $query->logicalOr($kantons);
			}
		}

		if (count($constraints)) {
			return $query->matching(
				$query->logicalAnd($constraints)
			)->execute();
		} else {
			return FALSE;
		}
	}

}
?>