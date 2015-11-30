<?php
namespace Visol\Easyvote\Domain\Model;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * City
 */
class City extends AbstractEntity
{

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the postalCode
     *
     * @return string $postalCode
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Sets the postalCode
     *
     * @param string $postalCode
     * @return void
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * Returns the municipality
     *
     * @return string $municipality
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * Sets the municipality
     *
     * @param string $municipality
     * @return void
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * Returns the longitude
     *
     * @return string $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @param string $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude
     *
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return \Visol\Easyvote\Domain\Model\Kanton
     */
    public function getKanton()
    {
        return $this->kanton;
    }

    /**
     * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
     */
    public function setKanton($kanton)
    {
        $this->kanton = $kanton;
    }

}