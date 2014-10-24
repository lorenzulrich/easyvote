<?php
namespace Visol\Easyvote\Domain\Model;


/***************************************************************
 *
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
 * City
 */
class City extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Name
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $name = '';

	/**
	 * Postal Code
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $postalCode = '';

	/**
	 * Municipality
	 *
	 * @var string
	 */
	protected $municipality = '';

	/**
	 * Longitude
	 *
	 * @var string
	 */
	protected $longitude = '';

	/**
	 * Latitude
	 *
	 * @var string
	 */
	protected $latitude = '';

	/**
	 * Kanton
	 *
	 * @var \Visol\Easyvote\Domain\Model\Kanton
	 */
	protected $kanton;

	/**
	 * Returns the name
	 *
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the postalCode
	 *
	 * @return string $postalCode
	 */
	public function getPostalCode() {
		return $this->postalCode;
	}

	/**
	 * Sets the postalCode
	 *
	 * @param string $postalCode
	 * @return void
	 */
	public function setPostalCode($postalCode) {
		$this->postalCode = $postalCode;
	}

	/**
	 * Returns the municipality
	 *
	 * @return string $municipality
	 */
	public function getMunicipality() {
		return $this->municipality;
	}

	/**
	 * Sets the municipality
	 *
	 * @param string $municipality
	 * @return void
	 */
	public function setMunicipality($municipality) {
		$this->municipality = $municipality;
	}

	/**
	 * Returns the longitude
	 *
	 * @return string $longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Sets the longitude
	 *
	 * @param string $longitude
	 * @return void
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

	/**
	 * Returns the latitude
	 *
	 * @return string $latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Sets the latitude
	 *
	 * @param string $latitude
	 * @return void
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	/**
	 * @return \Visol\Easyvote\Domain\Model\Kanton
	 */
	public function getKanton() {
		return $this->kanton;
	}

	/**
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
	 */
	public function setKanton($kanton) {
		$this->kanton = $kanton;
	}

}