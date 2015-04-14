<?php
namespace Visol\Easyvote\Command;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ImportCommandController extends \Visol\Easyvote\Command\AbstractCommandController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\CityRepository
	 * @inject
	 */
	protected $cityRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
	 * @inject
	 */
	protected $communityUserRepository;

	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Import City and Postal Code data
	 *
	 * @param string $sourcePathAndFilename File to import
	 */
	public function importCityDataCommand($sourcePathAndFilename) {
		if (is_file($sourcePathAndFilename)) {
			$handle = fopen($sourcePathAndFilename, 'r');
			$i = 0;
			$messages = array();
			$messages[] = 'Importiere Daten aus Datei ' . $sourcePathAndFilename . LF;
			while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
				$i++;
				if ($i == 1) {
					continue;
				}
				if ($data[4] === 'LI') {
					// Ignore Fürstentum Liechtenstein
					continue;
				}
				if (empty($data[4])) {
					// Don't import cities without Kanton
					continue;
				}
				$existingCity = $this->cityRepository->findOneByNamePostalCodeMunicipality(utf8_encode($data[0]), $data[1], utf8_encode($data[3]));
				if ($existingCity instanceof \Visol\Easyvote\Domain\Model\City) {
					/** @var $existingCity \Visol\Easyvote\Domain\Model\City */
					$existingCity->setLongitude($data[5]);
					$existingCity->setLatitude($data[6]);
					/** @var \Visol\Easyvote\Domain\Model\Kanton $kanton */
					$kanton = $this->kantonRepository->findOneByAbbreviation($data[4]);
					if ($kanton instanceof \Visol\Easyvote\Domain\Model\Kanton) {
						$kanton->addCity($existingCity);
						$existingCity->setKanton($kanton);
						$this->kantonRepository->update($kanton);
					} else {
						$messages[] = '!!! Kein Kanton gefunden für Gemeinde ' . $existingCity->getName() . '.' . LF;
					}
					$this->cityRepository->update($existingCity);
					$messages[] = 'Gemeinde ' . $existingCity->getName() . ' aktualisiert.' . LF;

				} else {
					$city = new \Visol\Easyvote\Domain\Model\City;
					$city->setName(utf8_encode($data[0]));
					$city->setPostalCode($data[1]);
					$city->setMunicipality(utf8_encode($data[3]));
					$city->setLongitude($data[5]);
					$city->setLatitude($data[6]);
					if (!empty($data[4])) {
						/** @var \Visol\Easyvote\Domain\Model\Kanton $kanton */
						$kanton = $this->kantonRepository->findOneByAbbreviation(utf8_encode($data[4]));
						$kanton->addCity($city);
						$city->setKanton($kanton);
						$this->kantonRepository->update($kanton);
					} else {
						$messages[] = '!!! Kein Kanton gefunden für Gemeinde ' . $city->getName() . '.' . LF;
					}
					$this->cityRepository->add($city);
					$messages[] = 'Gemeinde ' . $city->getName() . ' importiert.' . LF;

				}

				$this->persistenceManager->persistAll();

			}

			$this->response->setContent(implode($messages));


		} else {
			$this->response->setContent('File "' . $sourcePathAndFilename . '" not found' . LF);
			$this->response->setExitCode(2);
		}

	}

	/**
	 * Generate an auth token for each community user and update the community user with it
	 */
	public function generateAuthTokenForCommunityUsersCommand() {
		$communityUsers = $this->communityUserRepository->findAll();
		$i = 0;
		foreach ($communityUsers as $communityUser) {
			/** @var \Visol\Easyvote\Domain\Model\CommunityUser $communityUser */
			$token = \Visol\Easyvote\Utility\Algorithms::generateRandomToken(20);
			$communityUser->setAuthToken($token);
			$this->communityUserRepository->update($communityUser);
			if ($i % 50) {
				$this->persistenceManager->persistAll();
			}
			$i++;
		}
		$this->persistenceManager->persistAll();
	}

}
?>