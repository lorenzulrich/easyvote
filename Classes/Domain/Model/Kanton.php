<?php
namespace Visol\Easyvote\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Lorenz Ulrich <lorenz.ulrich@visol.ch>, visol digitale Dienstleistungen GmbH
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
class Kanton extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Name
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * Abkürzung
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $abbreviation;

	/**
	 * Orte
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\City>
	 * @lazy
	 */
	protected $cities;

	/**
	 * Languages
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Language>
	 * @lazy
	 */
	protected $languages;

	/**
	 * __construct
	 *
	 * @return Kanton
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->cities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->languages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the name
	 *
	 * @return \string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param \string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the abbreviation
	 *
	 * @return \string $abbreviation
	 */
	public function getAbbreviation() {
		return $this->abbreviation;
	}

	/**
	 * Sets the abbreviation
	 *
	 * @param \string $abbreviation
	 * @return void
	 */
	public function setAbbreviation($abbreviation) {
		$this->abbreviation = $abbreviation;
	}

	/**
	 * Adds a City
	 *
	 * @param \Visol\Easyvote\Domain\Model\City $city
	 * @return void
	 */
	public function addCity(\Visol\Easyvote\Domain\Model\City $city) {
		$this->cities->attach($city);
	}

	/**
	 * Removes a City
	 *
	 * @param \Visol\Easyvote\Domain\Model\City $cityToRemove The City to be removed
	 * @return void
	 */
	public function removeCity(\Visol\Easyvote\Domain\Model\City $cityToRemove) {
		$this->cities->detach($cityToRemove);
	}

	/**
	 * Returns the cities
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\City> $cities
	 */
	public function getCities() {
		return $this->cities;
	}

	/**
	 * Sets the cities
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\City> $cities
	 * @return void
	 */
	public function setCities(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $cities) {
		$this->cities = $cities;
	}

	/**
	 * Adds a Language
	 *
	 * @param \Visol\Easyvote\Domain\Model\Language $language
	 * @return void
	 */
	public function addLanguage(\Visol\Easyvote\Domain\Model\Language $language) {
		$this->languages->attach($language);
	}

	/**
	 * Removes a Language
	 *
	 * @param \Visol\Easyvote\Domain\Model\Language $languageToRemove The language to be removed
	 * @return void
	 */
	public function removeLanguage(\Visol\Easyvote\Domain\Model\Language $languageToRemove) {
		$this->languages->detach($languageToRemove);
	}

	/**
	 * Returns the languages
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Language> $languages
	 */
	public function getLanguages() {
		return $this->languages;
	}

	/**
	 * Sets the languages
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Language> $languages
	 * @return void
	 */
	public function setLanguages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $languages) {
		$this->languages = $languages;
	}

}
?>