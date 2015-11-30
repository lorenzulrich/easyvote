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

class Kanton extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

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
     * Limit the number of panels with panelInvitations
     *
     * @var int
     */
    protected $panelLimit;

    /**
     * __construct
     *
     * @return Kanton
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->cities = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->languages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return \string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param \string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the abbreviation
     *
     * @return \string $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Sets the abbreviation
     *
     * @param \string $abbreviation
     * @return void
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

    /**
     * Adds a City
     *
     * @param \Visol\Easyvote\Domain\Model\City $city
     * @return void
     */
    public function addCity(\Visol\Easyvote\Domain\Model\City $city)
    {
        $this->cities->attach($city);
    }

    /**
     * Removes a City
     *
     * @param \Visol\Easyvote\Domain\Model\City $cityToRemove The City to be removed
     * @return void
     */
    public function removeCity(\Visol\Easyvote\Domain\Model\City $cityToRemove)
    {
        $this->cities->detach($cityToRemove);
    }

    /**
     * Returns the cities
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\City> $cities
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Sets the cities
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\City> $cities
     * @return void
     */
    public function setCities(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $cities)
    {
        $this->cities = $cities;
    }

    /**
     * Adds a Language
     *
     * @param \Visol\Easyvote\Domain\Model\Language $language
     * @return void
     */
    public function addLanguage(\Visol\Easyvote\Domain\Model\Language $language)
    {
        $this->languages->attach($language);
    }

    /**
     * Removes a Language
     *
     * @param \Visol\Easyvote\Domain\Model\Language $languageToRemove The language to be removed
     * @return void
     */
    public function removeLanguage(\Visol\Easyvote\Domain\Model\Language $languageToRemove)
    {
        $this->languages->detach($languageToRemove);
    }

    /**
     * Returns the languages
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Language> $languages
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Sets the languages
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Visol\Easyvote\Domain\Model\Language> $languages
     * @return void
     */
    public function setLanguages(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return int
     */
    public function getPanelLimit()
    {
        return $this->panelLimit;
    }

    /**
     * @param int $panelLimit
     */
    public function setPanelLimit($panelLimit)
    {
        $this->panelLimit = $panelLimit;
    }

}