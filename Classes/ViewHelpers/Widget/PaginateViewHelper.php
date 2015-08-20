<?php

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

/**
 * Class Tx_Easyvote_ViewHelpers_Widget_PaginateViewHelper
 *
 * This is an improved Paginate ViewHelper that allows specifying additionalParams
 */
class Tx_Easyvote_ViewHelpers_Widget_PaginateViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * @var Tx_Easyvote_ViewHelpers_Widget_Controller_PaginateController
	 */
	protected $controller;

	/**
	 * @param Tx_Easyvote_ViewHelpers_Widget_Controller_PaginateController $controller
	 * @return void
	 */
	public function injectController(Tx_Easyvote_ViewHelpers_Widget_Controller_PaginateController $controller) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @param Tx_Extbase_Persistence_QueryResultInterface $objects
	 * @param string $as
	 * @param array $additionalParams
	 * @param string $additionalParamsPrefix
	 * @param array $configuration
	 * @return string
	 */
	public function render(Tx_Extbase_Persistence_QueryResultInterface $objects, $as, $additionalParams = array(), $additionalParamsPrefix = '', array $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'maximumNumberOfLinks' => 99)) {
		return $this->initiateSubRequest();
	}
}
