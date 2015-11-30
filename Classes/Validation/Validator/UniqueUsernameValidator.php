<?php
namespace Visol\Easyvote\Validation\Validator;

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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class UniqueUsernameValidator extends AbstractValidator
{

    /**
     * communityUserRepository
     *
     * @var \Visol\Easyvote\Domain\Repository\CommunityUserRepository
     * @inject
     */
    protected $communityUserRepository;

    /**
     * Check if there is no user with username $value
     * If there is, add an error
     *
     * @param string $value
     * @return void
     */
    protected function isValid($value)
    {
        if (count($this->communityUserRepository->findByUsername($value))) {
            $this->addError(LocalizationUtility::translate('error.1413988036', 'easyvote'), 1413988036);
        }
    }

}