<?php
namespace Visol\Easyvote\Validation\Validator;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class UniqueUsernameValidator extends AbstractValidator {

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
	protected function isValid($value) {
		if (count($this->communityUserRepository->findByUsername($value))) {
			$this->addError(LocalizationUtility::translate('error.1413988036', 'easyvote'), 1413988036);
		}
	}

}