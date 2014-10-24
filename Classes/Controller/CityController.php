<?php
namespace Visol\Easyvote\Controller;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CityController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * kantonRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository;

	/**
	 * cityRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\CityRepository
	 * @inject
	 */
	protected $cityRepository;

	/**
	 * action listCitiesByPostalCode
	 *
	 * @return string
	 */
	public function listCitiesByPostalCodeAction() {
		$queryString = $GLOBALS['TYPO3_DB']->escapeStrForLike($GLOBALS['TYPO3_DB']->quoteStr(GeneralUtility::_GP('q'), 'tx_easyvote_domain_model_city'), 'tx_easyvote_domain_model_city');
		$cities = $this->cityRepository->findCitiesByPostalCodePart($queryString);

		$returnArray['results'] = array();
		foreach ($cities as $city) {
			/** @var $city \Visol\Easyvote\Domain\Model\City */
			$returnArray['results'][] = array(
				'id' => $city->getUid(),
				'text' => $city->getPostalCode() . ' ' . $city->getName(),
				'postalCode' => $city->getPostalCode(),
				'city' => $city->getName(),
				'kanton' => $city->getKanton()->getUid(),
				'kantonName' => $city->getKanton()->getName()
			);
		}
		$returnArray['more'] = FALSE;
		return json_encode($returnArray);
	}

}
?>