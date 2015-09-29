<?php
namespace Visol\Easyvote\Controller;

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

use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Visol\Easyvote\Domain\Model\City;
use Visol\Easyvote\Domain\Model\CommunityUser;
use Visol\Easyvote\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

class CommunityUserController extends \Visol\Easyvote\Controller\AbstractController {

	/**
	 * @var \Visol\Easyvote\Domain\Repository\KantonRepository
	 * @inject
	 */
	protected $kantonRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\VotingDayRepository
	 * @inject
	 */
	protected $votingDayRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository
	 * @inject
	 */
	protected $frontendUserGroupRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\PartyRepository
	 * @inject
	 */
	protected $partyRepository;

	/**
	 * @var \Visol\Easyvote\Domain\Repository\MessagingJobRepository
	 * @inject
	 */
	protected $messagingJobRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * action userOverview
	 *
	 * @return void
	 */
	public function userOverviewAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		$this->view->assign('user', $communityUser);
	}

	/**
	 * action profilePicture
	 *
	 * @return void
	 */
	public function profilePictureAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		$this->view->assign('user', $communityUser);
	}

	/**
	 * action userFunctions
	 *
	 * @return void
	 */
	public function userFunctionsAction() {
	}

	/**
	 * action noProfileNotification
	 *
	 * @return void
	 */
	public function noProfileNotificationAction() {
	}

	/**
	 * action notAuthenticatedModal
	 *
	 * @return void
	 */
	public function notAuthenticatedModalAction() {
	}

	/**
	 * action loginPanel
	 *
	 * @return void
	 */
	public function loginPanelAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		if ($communityUser instanceof CommunityUser) {
			$this->view->assign('user', $communityUser);
		}
	}

	/**
	 * action editProfile
	 * @param boolean $goToTeacherProfile
	 * @param boolean $goToPoliticianProfile
	 */
	public function editProfileAction($goToTeacherProfile = NULL, $goToPoliticianProfile = NULL) {
		$communityUser = $this->communityUserService->getCommunityUser();

		if ($communityUser instanceof CommunityUser) {
			$fullPhoneNumber = $communityUser->getTelephone();
			$allowedPhoneNumberPrefixes = $this->settings['allowedPhoneNumberPrefixes'];
			foreach ($allowedPhoneNumberPrefixes as $key => $phoneNumberPrefix) {
				if (GeneralUtility::isFirstPartOfStr($fullPhoneNumber, $key)) {
					$prefixCode = substr($fullPhoneNumber, 0, strlen($key));
					$phoneNumber = substr($fullPhoneNumber, strlen($key));
					break;
				}
			}
			$communityUser->setPrefixCode($prefixCode);
			$communityUser->setTelephoneWithoutPrefix($phoneNumber);
			$this->view->assign('phoneNumberPrefixes', $allowedPhoneNumberPrefixes);
			$this->view->assign('parties', $this->partyRepository->findAll());
			$this->view->assign('user', $communityUser);

			// Education Types (selection saved as a string)
			$educationTypes = [];
			$grammarSchoolLabel = LocalizationUtility::translate('editProfile.politician.educationType.grammarSchool', $this->request->getControllerExtensionName());
			$educationTypes[$grammarSchoolLabel] = $grammarSchoolLabel;
			$vocationalBusinessSchoolLabel = LocalizationUtility::translate('editProfile.politician.educationType.vocationalBusinessSchool', $this->request->getControllerExtensionName());
			$educationTypes[$vocationalBusinessSchoolLabel] = $vocationalBusinessSchoolLabel;
			$specializedVocationalSchoolLabel = LocalizationUtility::translate('editProfile.politician.educationType.specializedVocationalSchool', $this->request->getControllerExtensionName());
			$educationTypes[$specializedVocationalSchoolLabel] = $specializedVocationalSchoolLabel;
			$otherLabel = LocalizationUtility::translate('editProfile.politician.educationType.other', $this->request->getControllerExtensionName());
			$educationTypes[$otherLabel] = $otherLabel;
			$this->view->assign('educationTypes', $educationTypes);

			// Scroll to teacher and politician section
			if (($goToTeacherProfile && $goToPoliticianProfile) || $goToTeacherProfile) {
				$this->view->assign('goToTeacherProfile', TRUE);
			} elseif ($goToPoliticianProfile) {
				$this->view->assign('goToPoliticianProfile', TRUE);
			}

		}
	}

	/**
	 * Allow all properties of communityUser
	 * Convert birthdate to DateTime
	 */
	protected function initializeUpdateProfileAction(){
		$propertyMappingConfiguration = $this->arguments['communityUser']->getPropertyMappingConfiguration();
		$uploadConfiguration = array(
			UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/userimages/',
		);
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->forProperty('falImage')
			->setTypeConverterOptions(
				'Visol\\Easyvote\\Property\\TypeConverter\\UploadedFileReferenceConverter',
				$uploadConfiguration
			);
		$propertyMappingConfiguration->forProperty('birthdate')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'd.m.Y');
	}

	/**
	 * action updateProfile
	 *
	 * @param CommunityUser $communityUser
	 * @param string $phoneNumberPrefix
	 * @param boolean $politician
	 * @param boolean $teacher
	 * @param boolean $goToPoliticianProfile
	 * @param boolean $goToTeacherProfile
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
	 */
	public function updateProfileAction(CommunityUser $communityUser, $phoneNumberPrefix = '4175', $politician = NULL, $teacher = NULL, $goToPoliticianProfile = NULL, $goToTeacherProfile = NULL) {
		$loggedInUser = $this->communityUserService->getCommunityUser();
		/** Todo: Sanitize properties that should never be updated by the user. */
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			if ($communityUser->_isDirty('email')) {
				// e-mail address changed, so validate
				/** @var \Visol\Easyvote\Validation\Validator\UniqueUsernameValidator $uniqueUsernameValidator */
				$uniqueUsernameValidator = $this->objectManager->get('Visol\Easyvote\Validation\Validator\UniqueUsernameValidator');
				$validatedUsername = $uniqueUsernameValidator->validate($communityUser->getEmail());
				if (count($validatedUsername->getErrors())) {
					// e-mail address is already in use, throw error
					$this->addFlashMessage($validatedUsername->getFirstError()->getMessage(), $validatedUsername->getFirstError()->getTitle(), \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
					$this->redirect('editProfile');
				} else {
					// e-mail address is not in use, so function can continue, but user needs to opt-in again
					// TODO add "soft" re-optin because setDisable(1) logs out the user
					//$communityUser->setDisable(1);
					if ($this->communityUserService->hasRole($communityUser, 'communityEmail')) {
						// and set username = new e-mail if it's not a Facebook user
						$communityUser->setUsername($communityUser->getEmail());
					}
				}
			}

			// General functions
			if (array_key_exists($phoneNumberPrefix, $this->settings['allowedPhoneNumberPrefixes']) && $communityUser->_isDirty('telephone')) {
				$communityUser->setTelephone($phoneNumberPrefix . preg_replace('/\D/', '', $communityUser->getTelephone()));
			} else {
				$communityUser->setTelephone($this->settings['allowedPhoneNumberPrefixes'][0] . preg_replace('/\D/', '', $communityUser->getTelephone()));
			}
			if ($communityUser->getCitySelection() instanceof City) {
				$communityUser->setKanton($communityUser->getCitySelection()->getKanton());
			}
			if (empty($communityUser->getAuthToken())) {
				// generate an auth token if user doesn't have one yet
				$communityUser->setAuthToken(\Visol\Easyvote\Utility\Algorithms::generateRandomToken(20));
			}

			// This needs to be the first message
			$this->addFlashMessage(LocalizationUtility::translate('editProfile.saved', 'easyvote'));

			// Teacher functions
			if ($teacher === TRUE && !$loggedInUser->isTeacher()) {
				// User changed state to teacher
				$communityUser->addUsergroup($this->communityUserService->getUserGroup('teacher'));
				$managePanelsUri = $this->uriBuilder->setTargetPageUid($this->settings['managePanelsPid'])->build();
				$this->addFlashMessage(LocalizationUtility::translate('editProfile.teacherFunctionsEnabled', 'easyvote', array($managePanelsUri)));
			} elseif ($teacher === FALSE && $loggedInUser->isTeacher()) {
				// User changed state to non-teacher, so we remove the group
				$communityUser->removeUsergroup($this->communityUserService->getUserGroup('teacher'));
				// Empty the school-related fields
				$communityUser->setOrganization('');
				$communityUser->setOrganizationWebsite('');
				$communityUser->setOrganizationCity(NULL);
			}

			// Politician functions
			if ($politician === TRUE && !$loggedInUser->isPendingPoliticianOrPolitician()) {
				// User changed state to politician, so we add them to the pendingPolitician usergroup
				$communityUser->addUsergroup($this->communityUserService->getUserGroup('pendingPolitician'));
				$this->addFlashMessage(LocalizationUtility::translate('editProfile.pendingPoliticianNotification', 'easyvote'));

				// Notify all party administrators
				/** @var \Visol\Easyvote\Service\TemplateEmailService $templateEmail */
				$templateEmail = $this->objectManager->get('Visol\Easyvote\Service\TemplateEmailService');
				$templateEmail->setTemplateName('communityUserPendingPartyAdministrator');
				$templateEmail->setExtensionName($this->request->getControllerExtensionName());
				$templateEmail->assign('pendingPolitician', $communityUser);
				$partyAdministrators = $this->communityUserRepository->findPartyAdministrators($communityUser->getParty());
				foreach ($partyAdministrators as $partyAdministrator) {
					/** @var $partyAdministrator \Visol\Easyvote\Domain\Model\CommunityUser */
					$templateEmail->addRecipient($partyAdministrator);
				}
				$templateEmail->enqueue();
			} elseif ($politician === FALSE && $loggedInUser->isPendingPolitician()) {
				// User changed state to non-politician, so we remove the group
				$communityUser->removeUsergroup($this->communityUserService->getUserGroup('pendingPolitician'));
				$communityUser->setParty(NULL);
			} elseif ($politician === FALSE && $loggedInUser->isPolitician()) {
				// User changed state to non-politician, so we remove the group
				$communityUser->removeUsergroup($this->communityUserService->getUserGroup('politician'));
				$communityUser->setParty(NULL);
			} elseif ($communityUser->_isDirty('party')) {
				// Make politician a pending politician if party was changed
				// _isDirty only works correctly if the party property has no @lazy annotation
				$communityUser->removeUsergroup($this->communityUserService->getUserGroup('politician'));
				$communityUser->removeUsergroup($this->communityUserService->getUserGroup('partyAdministrator'));
				$communityUser->addUsergroup($this->communityUserService->getUserGroup('pendingPolitician'));
				$this->addFlashMessage(LocalizationUtility::translate('editProfile.pendingPoliticianNotification', 'easyvote'));

				// Notify all party administrators
				/** @var \Visol\Easyvote\Service\TemplateEmailService $templateEmail */
				$templateEmail = $this->objectManager->get('Visol\Easyvote\Service\TemplateEmailService');
				$templateEmail->setTemplateName('communityUserPendingPartyAdministrator');
				$templateEmail->setExtensionName($this->request->getControllerExtensionName());
				$templateEmail->assign('pendingPolitician', $communityUser);
				$partyAdministrators = $this->communityUserRepository->findPartyAdministrators($communityUser->getParty());
				foreach ($partyAdministrators as $partyAdministrator) {
					/** @var $partyAdministrator \Visol\Easyvote\Domain\Model\CommunityUser */
					$templateEmail->addRecipient($partyAdministrator);
				}
				$templateEmail->enqueue();
			}

			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
		}
		$arguments = [
			'goToTeacherProfile' => $goToTeacherProfile,
			'goToPoliticianProfile' => $goToPoliticianProfile,
		];
		$this->redirect('editProfile', NULL, NULL, $arguments);
	}

	/**
	 * action removeProfile
	 *
	 * @param CommunityUser $communityUser
	 * @dontvalidate $communityUser
	 */
	public function removeProfileAction(CommunityUser $communityUser) {
		$loggedInUser = $this->communityUserService->getCommunityUser();
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			$communityUser->setEmail('');
			$communityUser->setNotificationMailActive(0);
			$communityUser->setCommunityNewsMailActive(0);
			$communityUser->setTelephone('');
			$communityUser->setNotificationSmsActive(0);
			$communityUser->setUsername('gelöschter Benutzer');
			$communityUser->setFirstName('gelöschter Benutzer');
			$communityUser->setLastName('');
			$communityUser->setAddress('');
			$communityUser->setZip('');
			$communityUser->setCity('');
			$communityUser->setBirthdate(NULL);
			$communityUser->setCitySelection(NULL);
			$communityUser->setParty(NULL);
			foreach ($communityUser->getUsergroup() as $usergroup) {
				/** @var $usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
				$communityUser->removeUsergroup($usergroup);
			}
			$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityUserGroupUid']);
			$communityUser->removeUsergroup($communityUserGroup);
			$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityFacebookUserGroupUid']);
			$communityUser->removeUsergroup($communityUserGroup);
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$siteHomeUri = $this->uriBuilder->setTargetPageUid($this->settings['siteHomePid'])->setArguments(array('logintype' => 'logout'))->setCreateAbsoluteUri(TRUE)->build();
			$arguments = array('redirectUri' => $siteHomeUri);
			foreach ($communityUser->getUsergroup() as $usergroup) {
				/** @var $usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
				if ($usergroup->getUid() === (int)$this->settings['communityFacebookUserGroupUid']) {
					$this->redirect('revokePermissions', 'Authentication', 'Vifbauth', $arguments, $this->settings['loginPid']);
				}
			}
			$this->redirectToUri($siteHomeUri);

		}
	}

	/**
	 * action editNotifications
	 */
	public function editNotificationsAction() {
		$communityUser = $this->communityUserService->getCommunityUser();
		if ($communityUser instanceof CommunityUser) {
			$fullPhoneNumber = $communityUser->getTelephone();
			$allowedPhoneNumberPrefixes = $this->settings['allowedPhoneNumberPrefixes'];
			foreach ($allowedPhoneNumberPrefixes as $key => $phoneNumberPrefix) {
				if (GeneralUtility::isFirstPartOfStr($fullPhoneNumber, $key)) {
					$prefixCode = substr($fullPhoneNumber, 0, strlen($key));
					$phoneNumber = substr($fullPhoneNumber, strlen($key));
					break;
				}
			}
			$communityUser->setPrefixCode($prefixCode);
			$communityUser->setTelephoneWithoutPrefix($phoneNumber);
			$this->view->assign('user', $communityUser);
			$this->view->assign('phoneNumberPrefixes', $this->settings['allowedPhoneNumberPrefixes']);
		}
	}

	/**
	 * action updateNotifications
	 *
	 * @param CommunityUser $communityUser
	 * @param string $phoneNumberPrefix
	 */
	public function updateNotificationsAction(CommunityUser $communityUser, $phoneNumberPrefix = '4175') {
		$loggedInUser = $this->communityUserService->getCommunityUser();
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			if ($communityUser->_isDirty('email')) {
				// e-mail address changed, so validate
				/** @var \Visol\Easyvote\Validation\Validator\UniqueUsernameValidator $uniqueUsernameValidator */
				$uniqueUsernameValidator = $this->objectManager->get('Visol\Easyvote\Validation\Validator\UniqueUsernameValidator');
				$validatedUsername = $uniqueUsernameValidator->validate($communityUser->getEmail());
				if (count($validatedUsername->getErrors())) {
					// e-mail address is already in use, throw error
					$this->addFlashMessage($validatedUsername->getFirstError()->getMessage(), $validatedUsername->getFirstError()->getTitle(), \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
					$this->redirect('editNotifications');
				} else {
					// e-mail address is not in use, so function can continue, but user needs to opt-in again
					// TODO add "soft" re-optin because setDisable(1) logs out the user
					//$communityUser->setDisable(1);
					// and set username = new e-mail
					if ($this->communityUserService->hasRole($communityUser, 'communityEmail')) {
						// and set username = new e-mail if it's not a Facebook user
						$communityUser->setUsername($communityUser->getEmail());
					}
				}
			}
			if (array_key_exists($phoneNumberPrefix, $this->settings['allowedPhoneNumberPrefixes'])) {
				$communityUser->setTelephone($phoneNumberPrefix . preg_replace('/\D/', '', $communityUser->getTelephone()));
			} else {
				$communityUser->setTelephone('4175' . preg_replace('/\D/', '', $communityUser->getTelephone()));
			}
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$this->addFlashMessage(LocalizationUtility::translate('editNotifications.saved', 'easyvote'));

		}
		$this->redirect('editNotifications');
	}

	/**
	 * Unsubscribe user from notification
	 */
	public function unsubscribeFromNotificationAction() {
		$arguments = GeneralUtility::_GET();
		if (isset($arguments['cuid']) && isset($arguments['verify'])) {
			$communityUserUid = (int)base64_decode($arguments['cuid']);
			$validVerificationCode = GeneralUtility::stdAuthCode($communityUserUid);
			if ($validVerificationCode === $arguments['verify']) {
				$message = 'valid';
				/** @var \Visol\Easyvote\Domain\Model\CommunityUser $communityUser */
				$communityUser = $this->communityUserRepository->findByUid($communityUserUid);
				$userGroups = $communityUser->getUsergroup();
				$userGroupArray = array();
				foreach ($userGroups as $userGroup) {
					/** @var $userGroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
					$userGroupArray[] = $userGroup->getUid();
				}
				if (count($userGroupArray)) {
					if (in_array($this->settings['communityUserGroupUid'], $userGroupArray)) {
						// Facebook: Deactivate notification mail
						$communityUser->setNotificationMailActive(FALSE);
						$this->communityUserRepository->update($communityUser);
						$this->persistenceManager->persistAll();
						$message = 	LocalizationUtility::translate('unsubscribe.notificationMailDisabled', 'easyvote');
					} else {
						$message = LocalizationUtility::translate('unsubscribe.invalidRequest', 'easyvote');
					}
				} else {
					$message = LocalizationUtility::translate('unsubscribe.invalidRequest', 'easyvote');
				}
			} else {
				$message = LocalizationUtility::translate('unsubscribe.invalidRequest', 'easyvote');
			}
		} else {
			$message = LocalizationUtility::translate('unsubscribe.invalidRequest', 'easyvote');
		}
		$this->view->assign('message', $message);
	}

	/**
	 * The backend dashboard
	 */
	public function backendDashboardAction() {
	}

	/**
	 * Interface for exporting mail addresses
	 */
	public function backendEmailExportIndexAction() {
		$newsletters = array(
			'Vote-Wecker' => CommunityUser::NEWSLETTER_VOTING,
			'Community-News' => CommunityUser::NEWSLETTER_COMMUNITY,
		);

		$languages = array(
			'Deutsch' => CommunityUser::USERLANGUAGE_GERMAN,
			'Französisch' => CommunityUser::USERLANGUAGE_FRENCH,
			'Italienisch' => CommunityUser::USERLANGUAGE_ITALIAN
		);

		$kantons = $this->kantonRepository->findAll();

		$dateTime = new \DateTime();
		$dateTime = $dateTime->format('Y-m-d\TH:i:s');

		$this->view->assignMultiple(array(
			'newsletters' => $newsletters,
			'languages' => $languages,
			'kantons' => $kantons,
			'dateTime' => $dateTime,
		));
	}

	/**
	 * @param array $demand
	 */
	public function backendEmailExportPerformAction($demand = array()) {
		if (is_array($demand['filter'])) {
			$demand['filter']['type'] = \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_EMAIL;
			$communityUsers = $this->communityUserRepository->findByFilterDemand($demand['filter']);

			// Create new PHPExcel object
			$objPHPExcel = new \PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("easyvote")
				->setLastModifiedBy("easyvote")
				->setTitle("easyvote Datenexport")
				->setSubject("easyvote Datenexport");

			$rowIndex = 1;

			// Add headers
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $rowIndex, 'Vorname')
				->setCellValue('B' . $rowIndex, 'Nachname')
				->setCellValue('C' . $rowIndex, 'E-Mail')
				->setCellValue('D' . $rowIndex, 'Kanton')
				->setCellValue('E' . $rowIndex, 'Sprache')
				->setCellValue('F' . $rowIndex, 'Typ')
				->setCellValue('G' . $rowIndex, 'Abmelde-Link');
			$rowIndex++;

			// Add content
			foreach ($communityUsers as $communityUser) {
				/** @var $communityUser CommunityUser */

				// kanton
				$kanton = is_object($communityUser->getCitySelection()) ? $communityUser->getCitySelection()->getKanton()->getName() : '';

				$unsubscribeUrl = array();
				$unsubscribeUrl[] = $this->settings['unsubscribeHost'];
				//$unsubscribeUrl[] = '?id=' . $this->settings['unsubscribePid'];

				// user language
				$userLanguage = $communityUser->getUserLanguage();
				if ($userLanguage === CommunityUser::USERLANGUAGE_GERMAN) {
					$userLanguage = 'Deutsch';
					$unsubscribeUrl[] = 'de/community/abmelden/';
				}
				if ($userLanguage === CommunityUser::USERLANGUAGE_FRENCH) {
					$userLanguage = 'Français';
					$unsubscribeUrl[] = 'fr/community/supprimer/';
				}
				if ($userLanguage === CommunityUser::USERLANGUAGE_ITALIAN) {
					$userLanguage = 'Italiano';
					$unsubscribeUrl[] = 'it/community/cancellare/';
				}

				$unsubscribeUrl[] = '?cuid=' . base64_encode($communityUser->getUid());
				$unsubscribeUrl[] = '&verify=' . GeneralUtility::stdAuthCode($communityUser->getUid());

				// user groups
				$usergroups = $communityUser->getUsergroup();
				$userGroupsArray = array();
				foreach ($usergroups as $usergroup) {
					/** var @usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
					$userGroupsArray[] = $usergroup->getTitle();
				}
				$userGroupsCsv = implode(',', $userGroupsArray);

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $rowIndex, $communityUser->getFirstName())
					->setCellValue('B' . $rowIndex, $communityUser->getLastName())
					->setCellValue('C' . $rowIndex, $communityUser->getEmail())
					->setCellValue('D' . $rowIndex, $kanton)
					->setCellValue('E' . $rowIndex, $userLanguage)
					->setCellValue('F' . $rowIndex, $userGroupsCsv)
					->setCellValue('G' . $rowIndex, implode($unsubscribeUrl));
				$rowIndex++;
			}

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			// Redirect output to a client’s web browser (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="easyvote.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			die();
		} else {
			$this->addFlashMessage('Fehler: Keine Filter-Anfrage für Versand.', '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			$this->redirect('backendEmailExportIndex');
		}
	}

	/**
	 * Interface for sending SMS messages
	 */
	public function backendSmsMessagingIndexAction() {
		$languages = array(
			'Deutsch' => CommunityUser::USERLANGUAGE_GERMAN,
			'Französisch' => CommunityUser::USERLANGUAGE_FRENCH,
			'Italienisch' => CommunityUser::USERLANGUAGE_ITALIAN
		);

		$kantons = $this->kantonRepository->findAll();

		$dateTime = new \DateTime();
		$dateTime = $dateTime->format('Y-m-d\TH:i:s');

		$testUser = $this->communityUserRepository->findByUid($this->settings['smsTestUserUid']);

		$this->view->assignMultiple(array(
			'languages' => $languages,
			'kantons' => $kantons,
			'dateTime' => $dateTime,
			'testUser' => $testUser
		));

	}

	/**
	 * @param array $demand
	 */
	public function backendSmsMessageSendAction($demand = array()) {
		if ((int)$demand['sendToTestUser'] === 1) {
			// no need to process filter demand, we just queue one message
			$messagingJob = new \Visol\Easyvote\Domain\Model\MessagingJob();
			$messagingJob->setContent($demand['message']);
			$messagingJob->setType(\Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_SMS);
			$distributionTime = date_create($demand['distributionTime']);
			$messagingJob->setDistributionTime($distributionTime);
			$testUser = $this->communityUserRepository->findByUid($this->settings['smsTestUserUid']);
			$messagingJob->setCommunityUser($testUser);
			$messagingJob->setSubject('SMS-Test-Job');
			$this->messagingJobRepository->add($messagingJob);
			$this->addFlashMessage('Test-SMS wurde in Warteschlange gestellt.');
		} else {
			// we queue the message for all users
			if (is_array($demand['filter'])) {
				$demand['filter']['type'] = \Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_SMS;
				$communityUsers = $this->communityUserRepository->findByFilterDemand($demand['filter']);
				$distributionTime = date_create($demand['distributionTime']);
				$jobRandomValue = uniqid();
				$iterator = 1;
				foreach ($communityUsers as $communityUser) {
					$messagingJob = new \Visol\Easyvote\Domain\Model\MessagingJob();
					$messagingJob->setContent($demand['message']);
					$messagingJob->setType(\Visol\Easyvote\Domain\Model\MessagingJob::JOBTYPE_SMS);
					$messagingJob->setDistributionTime($distributionTime);
					$messagingJob->setCommunityUser($communityUser);
					$messagingJob->setSubject('SMS-Job ' . $jobRandomValue);
					$this->messagingJobRepository->add($messagingJob);
					if ($iterator % 20 == 0) {
						$this->persistenceManager->persistAll();
					}
					$iterator++;
				}
				$communityUsersCount = $communityUsers->count();
				$this->addFlashMessage('Es wurden ' . $communityUsersCount . ' SMS in die Warteschlange gestellt.');
			} else {
				$this->addFlashMessage('Fehler: Keine Filter-Anfrage für Versand.', '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
		}
		$this->persistenceManager->persistAll();
		$this->redirect('backendSmsMessagingIndex');
	}

	/**
	 * If someone wants to login on this action, this will fail because of missing arguments
	 * Redirect user to home page in this case
	 */
	public function initializeCreateAction() {
		$loginType = GeneralUtility::_GP('logintype');
		if (!empty($loginType) && in_array($loginType, array('login', 'logout'))) {
			$loginUri = $this->uriBuilder->setTargetPageUid($this->settings['loginPid'])->setCreateAbsoluteUri(TRUE)->build();
			$this->redirectToUri($loginUri);
		}
		/* Validate the username and redirect to the noProfileNotification if validation fails
		 By default, Extbase would redirect the the original request action which doesn't work here because this
		 action opens in a modal window. So we take care of it ourselves*/
		if ($this->arguments->hasArgument('username')) {
			/** @var \Visol\Easyvote\Validation\Validator\UniqueUsernameValidator $uniqueUsernameValidator */
			$uniqueUsernameValidator = $this->objectManager->get('Visol\Easyvote\Validation\Validator\UniqueUsernameValidator');
			$validatedUsername = $uniqueUsernameValidator->validate($this->request->getArgument('username'));
			if (count($validatedUsername->getErrors())) {
				$this->flashMessageContainer->add($validatedUsername->getFirstError()->getMessage(), $validatedUsername->getFirstError()->getTitle(), \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
				$this->forward('noProfileNotification');
			}
		}
	}

	/**
	 * Create (inactive) e-mail based account
	 *
	 * @param string $username
	 * @validate $username EmailAddress
	 * @validate $username Visol\Easyvote\Validation\Validator\UniqueUsernameValidator
	 * @param string $password
	 * @validate $password NotEmpty
	 * @param string $firstName
	 * @validate $firstName NotEmpty
	 * @param string $lastName
	 * @validate $lastName NotEmpty
	 */
	public function createAction($username, $password, $firstName, $lastName) {
		// MD5 as fallback
		$saltedPassword = md5($password);
		// Create salted password
		if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('saltedpasswords')) {
			if (\TYPO3\CMS\Saltedpasswords\Utility\SaltedPasswordsUtility::isUsageEnabled('FE')) {
				$objSalt = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance(NULL);
				if (is_object($objSalt)) {
					$saltedPassword = $objSalt->getHashedPassword($password);
				}
			}
		}

		$userLanguage = CommunityUser::USERLANGUAGE_GERMAN;
		if ((int)$GLOBALS['TSFE']->sys_language_uid === 1) {
			$userLanguage = CommunityUser::USERLANGUAGE_FRENCH;
		}
		if ((int)$GLOBALS['TSFE']->sys_language_uid === 2) {
			$userLanguage = CommunityUser::USERLANGUAGE_ITALIAN;
		}


		/** @var \Visol\Easyvote\Domain\Model\CommunityUser $communityUser */
		$communityUser = $this->objectManager->get('Visol\Easyvote\Domain\Model\CommunityUser');
		$communityUser->setUsername($username);
		$communityUser->setEmail($username);
		$communityUser->setPassword($saltedPassword);
		$communityUser->setFirstName($firstName);
		$communityUser->setLastName($lastName);
		$communityUser->setPid($this->settings['userStoragePid']);
		$communityUser->setNotificationMailActive(1);
		$communityUser->setUserLanguage($userLanguage);
		$communityUser->setAuthToken(\Visol\Easyvote\Utility\Algorithms::generateRandomToken(20));
		$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityUserGroupUid']);
		$communityUser->addUsergroup($communityUserGroup);
		$communityEmailUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityEmailUserGroupUid']);
		$communityUser->addUsergroup($communityEmailUserGroup);
		$communityUser->setDisable(1);
		$this->communityUserRepository->add($communityUser);
		$this->persistenceManager->persistAll();

		// generate opt-in uri
		$arguments = array(
			array('tx_easyvote_community' =>
				array(
					'controller' => 'CommunityUser',
					'action' => 'activate',
					'cuid' => base64_encode($communityUser->getUid()),
					'verify' => GeneralUtility::stdAuthCode($communityUser->getUid()),
				)
			)
		);
		$optinUri = $this->uriBuilder->reset()->setUseCacheHash(FALSE)->setTargetPageUid($this->settings['communityRegistrationPid'])->setCreateAbsoluteUri(TRUE)->setArguments($arguments)->build();

		// send opt-in mail
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
		$standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
		$standaloneView->setFormat('html');
		$templatePathAndFilename = $this->resolveViewFileForStandaloneView('Template', 'Email/CreateOptin.html');
		$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
		$standaloneView->assign('communityUser', $communityUser);
		$standaloneView->assign('optinUri', $optinUri);
		$content = $standaloneView->render();

		/** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
		$message = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
		$message->setTo(array($username => $firstName . ' ' . $lastName));
		$message->setFrom(array($this->settings['senderEmail'] => $this->settings['senderName']));
		$message->setSubject(LocalizationUtility::translate('create.optinMail.subject', 'easyvote'));
		$message->setBody($content, 'text/html');
		$message->send();

	}

	/**
	 * Activate e-mail based account
	 */
	public function activateAction() {
		$arguments = $this->request->getArguments();
		if (isset($arguments['cuid']) && isset($arguments['verify'])) {
			$communityUserUid = (int)base64_decode($arguments['cuid']);
			$validVerificationCode = GeneralUtility::stdAuthCode($communityUserUid);
			if ($validVerificationCode === $arguments['verify']) {
				/** @var \Visol\Easyvote\Domain\Model\CommunityUser $communityUser */
				$communityUser = $this->communityUserRepository->findHiddenByUid($communityUserUid);
				if ($communityUser instanceof CommunityUser) {
					$communityUser->setDisable(0);
					$this->communityUserRepository->update($communityUser);
					$this->persistenceManager->persistAll();
					$this->loginUser($communityUser->getUsername());
					$this->view->assign('message', LocalizationUtility::translate('activate.successMessage', 'easyvote'));
				} else {
					// user not found
					$this->view->assign('message', LocalizationUtility::translate('activate.userNotFoundMessage', 'easyvote'));
				}
			} else {
				// provided verification code is incorrect
				$this->view->assign('message', LocalizationUtility::translate('activate.invalidLinkMessage', 'easyvote'));
			}
		} else {
			// link parts are missing
			$this->view->assign('message', LocalizationUtility::translate('activate.missingLinkPartsMessage', 'easyvote'));
		}
	}

	/**
	 * Request completion of user data
	 */
	public function dataCompletionRequestAction() {
		// try to get logged in user
		$communityUser = $this->communityUserService->getCommunityUser();

		$dataCompletionRequestNecessary = FALSE;

		if ($communityUser instanceof CommunityUser) {
			// check if modal has been displayed in current user session
			$modalWasDisplayedInSession = (boolean)$GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_easyvote_datacompletionrequestmodal');
			if (!$modalWasDisplayedInSession) {
				// if not, check if it's necessary to display it because some needed fields are empty
				if (!$communityUser->getCitySelection() instanceof City) {
					$dataCompletionRequestNecessary = TRUE;
				}
				if (!$communityUser->getBirthdate() instanceof \DateTime) {
					$dataCompletionRequestNecessary = TRUE;
				}
				if (!$communityUser->getFalImage() instanceof FileReference) {
					foreach ($communityUser->getUsergroup() as $usergroup) {
						/** @var $usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
						if ($usergroup->getUid() === (int)$this->settings['communityEmailUserGroupUid']) {
							$dataCompletionRequestNecessary = TRUE;
						}
					}
				}
				$this->view->assign('communityUser', $communityUser);
				// set session value to prevent the modal from being displayed another time in the current session
				$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_easyvote_datacompletionrequestmodal', 1);
				$GLOBALS['TSFE']->fe_user->sesData_change = TRUE;
				$GLOBALS['TSFE']->fe_user->storeSessionData();
			}
		}

		$this->view->assign('dataCompletionRequestNecessary', $dataCompletionRequestNecessary);
	}

	/**
	 * Generates configuration for the mobile app
	 *
	 * @return string
	 */
	public function appConfigurationAction() {
		// try to get logged in user
		$communityUser = $this->communityUserService->getCommunityUser();

		if ($communityUser instanceof CommunityUser) {
			$appConfiguration = array();
			$appConfiguration['results']['token'] = $communityUser->getAuthToken();
			$appConfiguration['results']['PushChannels'] = array();
			$appConfiguration['results']['PushChannels']['Channel0'] = 'userid_' . $communityUser->getUid();
			foreach ($this->settings['pushChannels'] as $key => $pushChannel) {
				$appConfiguration['results']['PushChannels']['Channel' . $key] = $pushChannel;
			}

			return json_encode($appConfiguration);

		} else {
			return json_encode(array());
		}

	}

	/**
	 * @param string $argumentName
	 */
	protected function setTypeConverterConfigurationForImageUpload($argumentName) {
	}

	/**
	 * Deactivate errorFlashMessage
	 *
	 * @return bool|string
	 */
	public function getErrorFlashMessage() {
		return FALSE;
	}

}
