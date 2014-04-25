<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Lorenz Ulrich <lorenz.ulrich@visol.ch>
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
 * ### Format: Append string content
 *
 * Appends a string after another string. Although this task is very
 * easily done in standard Fluid - i.e. {subject}{add} - this
 * ViewHelper makes advanced chained inline processing possible:
 *
 *     <!-- useful when needing to chain string processing. Remove all "foo" and "bar"
 *          then add a text containing both "foo" and "bar", then format as HTML -->
 *     {text -> v:format.eliminate(strings: 'foo,bar')
 *           -> v:format.append(add: ' - my foo and bar are the only ones in this text.')
 *           -> f:format.html()}
 *     <!-- NOTE: you do not have to break the lines; done here only for presentation purposes -->
 *
 * Makes no sense used as tag based ViewHelper:
 *
 *     <!-- DO NOT USE - depicts COUNTERPRODUCTIVE usage! -->
 *     <v:format.append add="{f:translate(key: 're')}">{subject}</v:format.append>
 *     <!-- ... which is the exact same as ... -->
 *     <f:translate key="re" />{subject} <!-- OR --> {f:translate(key: 're')}{subject}
 *
 * In other words: use this only when you do not have the option of
 * simply using {subject}{add}, i.e. in complex inline statements used
 * as attribute values on other ViewHelpers (where tag usage is undesirable).
 *
 * @author Claus Due <claus@wildside.dk>, Wildside A/S
 * @package Vhs
 * @subpackage ViewHelpers\Format
 */
class Tx_Easyvote_ViewHelpers_YoutubeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param string $videoUrl
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	public function render($videoUrl = '', $width=480, $height=360) {
		$embedCode = '';
		if (!empty($videoUrl)) {
			$urlArray = @parse_url($videoUrl);
			$videoQuery = $urlArray['query'];
			$videoId = t3lib_div::trimExplode('=', $videoQuery);
			$videoId = $videoId[1];
			$embedCode = '<iframe width="' . $width . '" height="' . $height . '" src="//www.youtube.com/embed/' . $videoId . '?html5=1&rel=0" frameborder="0" allowfullscreen></iframe>';
		}
		return $embedCode;
	}

}
