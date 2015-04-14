<?php
namespace Visol\Easyvote\Domain\Repository;


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
 * The repository for Cities
 */
class CityRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	public function findCitiesByPostalCodePart($queryString) {
		$query = $this->createQuery();
		$query->matching(
			$query->like('postalCode', $queryString . '%')
		);
		return $query->execute()->toArray();
	}

	/**
	 * @param $name
	 * @param $postalCode
	 * @param $municipality
	 * @return \Visol\Easyvote\Domain\Model\City|NULL
	 */
	public function findOneByNamePostalCodeMunicipality($name, $postalCode, $municipality) {
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->like('name', $name),
				$query->like('postalCode', $postalCode),
				$query->like('municipality', $municipality)
			)
		);
		return $query->execute()->getFirst();
	}

}