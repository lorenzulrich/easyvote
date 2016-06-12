<?php
namespace Visol\Easyvote\Grid;

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

use Fab\Vidi\Grid\ColumnRendererAbstract;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use Visol\Easyvote\Module\ModuleParameter;

/**
 * Class CantonRenderer
 */
class PersonalDataRequestRenderer extends ColumnRendererAbstract
{

	/**
	 * @return string
	 */
	public function render()
	{

		if ((int)$this->object['pid'] !== 144) {
			// Only allow export for Community Users
			return '';
		}

		$out = sprintf(
			'<div class="btn-group">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					Export
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="%s">HTML</a></li>
					<li class="todo-unhide hidden"><a href="%s">PDF</a></li>
				</ul>
			</div>',
			$this->getExportUri('html'),
			$this->getExportUri('pdf')
		);

		return $out;
	}


	/**
	 * @param string $format
	 * @return string
	 */
	protected function getExportUri($format = 'html')
	{
		$urlParameters = array(
			ModuleParameter::PREFIX => array(
				'controller' => 'CommunityUserBackend',
				'action' => 'personalDataRequest',
				'communityUser' => $this->getObject()->getUid(),
				'format' => $format
			),
		);
		return BackendUtility::getModuleUrl(ModuleParameter::MODULE_SIGNATURE, $urlParameters);
	}

}
