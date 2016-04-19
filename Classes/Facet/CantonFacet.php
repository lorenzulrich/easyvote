<?php
namespace Visol\Easyvote\Facet;

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

use Fab\Vidi\Facet\FacetInterface;
use Fab\Vidi\Persistence\Matcher;
use Fab\Vidi\Signal\AfterFindContentObjectsSignalArguments;

/**
 * Class CantonFacet
 */
class CantonFacet implements FacetInterface
{

    /**
     * @var string
     */
    protected $name = '__canton';

    /**
     * @var string
     */
    protected $label = 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.canton';

    /**
     * @var array
     */
    protected $suggestions = [
        '20' => 'Aargau',
        '15' => 'Appenzell Ausserrhoden',
        '16' => 'Appenzell Innerrhoden',
        '13' => 'Basel-Landschaft',
        '12' => 'Basel-Stadt',
        '2' => 'Bern',
        '10' => 'Freiburg',
        '26' => 'Genf',
        '8' => 'Glarus',
        '18' => 'Graubünden',
        '19' => 'Graubünden',
        '27' => 'Jura',
        '3' => 'Luzern',
        '25' => 'Neuenburg',
        '7' => 'Nidwalden',
        '6' => 'Obwalden',
        '14' => 'Schaffhausen',
        '5' => 'Schwyz',
        '11' => 'Solothurn',
        '17' => 'St. Gallen',
        '22' => 'Tessin',
        '21' => 'Thurgau',
        '4' => 'Uri',
        '23' => 'Waadt',
        '24' => 'Wallis',
        '9' => 'Zug',
        '1' => 'Zürich',
    ];

    /**
     * @var string
     */
    protected $fieldNameAndPath = '';

    /**
     * @var string
     */
    protected $dataType;

    /**
     * @var bool
     */
    protected $canModifyMatcher = true;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getLanguageService()->sL($this->label);
    }

    /**
     * @return array
     */
    public function getSuggestions()
    {
        $suggestions = array();
        foreach ($this->suggestions as $key => $label) {
            $suggestions[] = array($key => $this->getLanguageService()->sL($label));
        }

        return $suggestions;
    }

    /**
     * @return bool
     */
    public function hasSuggestions()
    {
        return true;
    }

    /**
     * @param string $dataType
     * @return $this
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @return bool
     */
    public function canModifyMatcher()
    {
        return $this->canModifyMatcher;
    }

    /**
     * @param Matcher $matcher
     * @param $value
     * @return Matcher
     */
    public function modifyMatcher(Matcher $matcher, $value)
    {
        $matcher->equals('city_selection.kanton', (int)$value);
        return $matcher;
    }

    /**
     * @param AfterFindContentObjectsSignalArguments $signalArguments
     * @return array
     */
//    public function modifyResultSet(AfterFindContentObjectsSignalArguments $signalArguments)
//    {
//        return array($signalArguments);
//    }

    /**
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}