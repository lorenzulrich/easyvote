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

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class KantonController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * kantonRepository
	 *
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository;

	/**
	 * action list
	 *
	 * @unused
	 * @return void
	 */
	public function listAction() {
		$kantons = $this->kantonRepository->findAll();
		$this->view->assign('kantons', $kantons);
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function kantonNavigationAction() {
		$requestedKanton = (int)\t3lib_div::_GET('tx_easyvote_currentvotings')['kanton'];
		if (!empty($requestedKanton)) {
			$this->view->assign('requestedKanton', $requestedKanton);
		}
		$kantons = $this->kantonRepository->findAll();
		$this->view->assignMultiple(array(
			'kantons' => $kantons,
		));
	}

	/**
	 * action show
	 *
	 * @unused
	 * @param \Visol\Easyvote\Domain\Model\Kanton $kanton
	 * @return void
	 */
	public function showAction(\Visol\Easyvote\Domain\Model\Kanton $kanton) {
		$this->view->assign('kanton', $kanton);
	}

}
?>