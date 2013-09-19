<?php

namespace Visol\Easyvote\Tests;
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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \Visol\Easyvote\Domain\Model\Kanton.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Lorenz Ulrich <lorenz.ulrich@visol.ch>
 */
class KantonTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \Visol\Easyvote\Domain\Model\Kanton
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \Visol\Easyvote\Domain\Model\Kanton();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getNameReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getName()
		);
	}

	/**
	 * @test
	 */
	public function setNameForStringSetsName() {
		$this->fixture->setName('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getName()
		);
	}
	/**
	 * @test
	 */
	public function getAbbreviationReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getAbbreviation()
		);
	}

	/**
	 * @test
	 */
	public function setAbbreviationForStringSetsAbbreviation() {
		$this->fixture->setAbbreviation('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getAbbreviation()
		);
	}
	/**
	 * @test
	 */
	public function getCitiesReturnsInitialValueForCity() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getCities()
		);
	}

	/**
	 * @test
	 */
	public function setCitiesForObjectStorageContainingCitySetsCities() {
		$city = new \Visol\Easyvote\Domain\Model\City();
		$objectStorageHoldingExactlyOneCities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCities->attach($city);
		$this->fixture->setCities($objectStorageHoldingExactlyOneCities);

		$this->assertSame(
			$objectStorageHoldingExactlyOneCities,
			$this->fixture->getCities()
		);
	}

	/**
	 * @test
	 */
	public function addCityToObjectStorageHoldingCities() {
		$city = new \Visol\Easyvote\Domain\Model\City();
		$objectStorageHoldingExactlyOneCity = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCity->attach($city);
		$this->fixture->addCity($city);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneCity,
			$this->fixture->getCities()
		);
	}

	/**
	 * @test
	 */
	public function removeCityFromObjectStorageHoldingCities() {
		$city = new \Visol\Easyvote\Domain\Model\City();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($city);
		$localObjectStorage->detach($city);
		$this->fixture->addCity($city);
		$this->fixture->removeCity($city);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getCities()
		);
	}
}
?>