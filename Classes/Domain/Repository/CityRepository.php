<?php
namespace Visol\Easyvote\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for Cities
 */
class CityRepository extends Repository
{

    /**
     * @param $name
     * @param $postalCode
     * @param $municipality
     * @return \Visol\Easyvote\Domain\Model\City|NULL
     */
    public function findOneByNamePostalCodeMunicipality($name, $postalCode, $municipality)
    {
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