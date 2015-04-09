<?php
namespace Visol\Easyvote\Property\TypeConverter;

/*                                                                        *
 * This script belongs to the Extbase framework                           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class TimestampConverter extends \TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter {

	const CONFIGURATION_DATE_FORMAT = 'dateFormat';

	/**
	 * Converts $source to a \DateTime using the configured dateFormat
	 *
	 * @param string|integer|array $source the string to be converted to a timestamp
	 * @param string $targetType must be "integer"
	 * @param array $convertedChildProperties not used currently
	 * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
	 * @return integer
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		if (empty($source)) {
			return NULL;
		}
		$dateFormat = $configuration->getConfigurationValue('Visol\\Easyvote\\Property\\TypeConverter\\TimestampConverter', self::CONFIGURATION_DATE_FORMAT);
		$output = \DateTime::createFromFormat($dateFormat, $source);
		return $output->getTimestamp();
	}

}
