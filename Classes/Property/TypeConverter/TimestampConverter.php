<?php
namespace Visol\Easyvote\Property\TypeConverter;

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

use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

/**
 * Class TimestampConverter
 */
class TimestampConverter extends AbstractTypeConverter
{

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
    public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration = NULL)
    {
        if (empty($source)) {
            return NULL;
        }
        $dateFormat = $configuration->getConfigurationValue('Visol\\Easyvote\\Property\\TypeConverter\\TimestampConverter', self::CONFIGURATION_DATE_FORMAT);
        $output = \DateTime::createFromFormat($dateFormat, $source);
        return $output->getTimestamp();
    }

}
