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
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Visol\Easyvote\Domain\Model\City;
use Visol\Easyvote\Domain\Model\CommunityUser;
use Visol\Easyvote\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

/**
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
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
		$communityUser = $this->getLoggedInUser();
		$this->view->assign('user', $communityUser);
	}

	/**
	 * action profilePicture
	 *
	 * @return void
	 */
	public function profilePictureAction() {
		$communityUser = $this->getLoggedInUser();
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
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof CommunityUser) {
			$this->view->assign('user', $communityUser);
		}
	}

	/**
	 * action editProfile
	 */
	public function editProfileAction() {
		$communityUser = $this->getLoggedInUser();
		$kantons = $this->kantonRepository->findAll();

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
			$this->view->assign('kantons', $kantons);
			$this->view->assign('phoneNumberPrefixes', $allowedPhoneNumberPrefixes);
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
	 */
	public function updateProfileAction(CommunityUser $communityUser, $phoneNumberPrefix = '4175') {
		$loggedInUser = $this->getLoggedInUser();
		/** Todo: Sanitize properties that should never be updated by the user. */
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			if (array_key_exists($phoneNumberPrefix, $this->settings['allowedPhoneNumberPrefixes'])) {
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
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$this->flashMessageContainer->add(LocalizationUtility::translate('editProfile.saved', 'easyvote'));
		}
		$this->redirect('editProfile');
	}

	/**
	 * action removeProfile
	 *
	 * @param CommunityUser $communityUser
	 * @dontvalidate $communityUser
	 */
	public function removeProfileAction(CommunityUser $communityUser) {
		$loggedInUser = $this->getLoggedInUser();
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			$communityUser->setEmail('');
			$communityUser->setNotificationMailActive(0);
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
			foreach ($communityUser->getUsergroup() as $usergroup) {
				/** @var $usergroup \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup */
				$communityUser->removeUsergroup($usergroup);
			}
			$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityUserGroupUid']);
			$communityUser->removeUsergroup($communityUserGroup);
			$communityUserGroup = $this->frontendUserGroupRepository->findByUid($this->settings['communityFacebookUserGroupUid']);
			$communityUser->removeUsergroup($communityUserGroup);
			$this->communityUserRepository->update($communityUser);
			// get all notificationRelatedUsers and remove them as well
			$notificationRelatedUsers = $communityUser->getNotificationRelatedUsers();
			foreach ($notificationRelatedUsers as $notificationRelatedUser) {
				$this->communityUserRepository->remove($notificationRelatedUser);
			}
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
		$communityUser = $this->getLoggedInUser();
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
	 * @dontvalidate $communityUser
	 */
	public function updateNotificationsAction(CommunityUser $communityUser, $phoneNumberPrefix = '4175') {
		$loggedInUser = $this->getLoggedInUser();
		/** Todo: Sanitize properties that should never be updated by the user. */
		if ($loggedInUser->getUid() === $communityUser->getUid()) {
			if (array_key_exists($phoneNumberPrefix, $this->settings['allowedPhoneNumberPrefixes'])) {
				$communityUser->setTelephone($phoneNumberPrefix . preg_replace('/\D/', '', $communityUser->getTelephone()));
			} else {
				$communityUser->setTelephone('4175' . preg_replace('/\D/', '', $communityUser->getTelephone()));
			}
			$this->communityUserRepository->update($communityUser);
			$this->persistenceManager->persistAll();
			$this->flashMessageContainer->add(LocalizationUtility::translate('editNotifications.saved', 'easyvote'));

		}
		$this->redirect('editNotifications');
	}

	/**
	 * action editNotifications
	 */
	public function editMobilizationsAction() {
		$communityUser = $this->getLoggedInUser();
		$nextVotingDay = $this->votingDayRepository->findNextVotingDay();
		if ($communityUser instanceof CommunityUser) {
			$this->view->assign('user', $communityUser);
			$this->view->assign('nextVotingDay', $nextVotingDay);
		}
	}

	/**
	 * action listMobilizedCommunityUsers
	 *
	 * @return string|boolean
	 */
	public function listMobilizedCommunityUsersAction() {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof CommunityUser) {
			$this->view->assign('user', $communityUser);
			$content = $this->view->render();
			return json_encode($content);
		} else {
			return json_encode(FALSE);
		}
	}

	/**
	 * action newMobilizedCommunityUser
	 *
	 * @return string
	 */
	public function newMobilizedCommunityUserAction() {
		$newCommunityUser = $this->objectManager->get('Visol\Easyvote\Domain\Model\CommunityUser');
		$this->view->assign('sysLanguageUid', $GLOBALS['TSFE']->sys_language_uid);
		$this->view->assign('newCommunityUser', $newCommunityUser);
		$content = $this->view->render();
		return json_encode($content);
	}

	/**
	 * Allow all properties of communityUser
	 */
	protected function initializeCreateMobilizedCommunityUserAction(){
		$propertyMappingConfiguration = $this->arguments['newCommunityUser']->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
	}

	/**
	 * action createMobilizedCommunityUser
	 *
	 * @param CommunityUser $newCommunityUser
	 * @dontvalidate $newCommunityUser
	 * @return string
	 */
	public function createMobilizedCommunityUserAction(CommunityUser $newCommunityUser) {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof CommunityUser) {
			// user is authorized to add users to his profile
			if (GeneralUtility::validEmail($newCommunityUser->getEmail())) {
				if (!count($this->communityUserRepository->findByEmail($newCommunityUser->getEmail()))) {
					$notificationRelatedUserGroupUid = (int)$this->settings['notificationRelatedUserGroupUid'];
					$notificationRelatedUserGroup = $this->frontendUserGroupRepository->findByUid($notificationRelatedUserGroupUid);
					$newCommunityUser->addUsergroup($notificationRelatedUserGroup);
					$newCommunityUser->setCommunityUser($communityUser);
					$newCommunityUser->setNotificationMailActive(1);
					$newCommunityUser->setUsername('vw-' . $newCommunityUser->getEmail());
					$newCommunityUser->setPassword(md5(GeneralUtility::generateRandomBytes(40)));
					$newCommunityUser->setPid($this->settings['userStoragePid']);
					$this->communityUserRepository->add($newCommunityUser);
					$this->persistenceManager->persistAll();

					/** @var \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob */
					$standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
					$standaloneView->setFormat('html');
					$templatePathAndFilename = $this->resolveViewFileForStandaloneView('Template', 'Email/MobilizedWelcomeMail.html');
					$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
					$nextVotingDay = $this->votingDayRepository->findNextVotingDay();
					$standaloneView->assign('nextVotingDay', $nextVotingDay);
					$standaloneView->assign('parentUser', $communityUser);
					$standaloneView->assign('mobilizedUser', $newCommunityUser);
					$content = $standaloneView->render();
					$messagingJob = $this->objectManager->get('Visol\Easyvote\Domain\Model\MessagingJob');
					$messagingJob->setSenderName($communityUser->getFirstName() . ' ' . $communityUser->getLastName());
					$messagingJob->setReturnPath($communityUser->getEmail());
					$messagingJob->setReplyTo($communityUser->getEmail());
					$messagingJob->setContent($content);
					$messagingJob->setSubject(LocalizationUtility::translate('mobilizedWelcomeMail.subject', 'easyvote'));
					$messagingJob->setCommunityUser($newCommunityUser);
					$messagingJob->setDistributionTime(new \DateTime());
					$messagingJob->setType($messagingJob::JOBTYPE_EMAIL);
					$this->messagingJobRepository->add($messagingJob);

					return json_encode(LocalizationUtility::translate('editMobilizations.saved', 'easyvote'));
				} else {
					return json_encode(LocalizationUtility::translate('editMobilizations.notSavedAlreadyMobilized', 'easyvote'));
				}
			} else {
				// e-mail invalid
				return json_encode(LocalizationUtility::translate('editMobilizations.notSavedEmailInvalid', 'easyvote'));
			}
		} else {
			return json_encode(FALSE);
		}
	}

	/**
	 * action removeMobilizedCommunityUser
	 *
	 * @param CommunityUser $notificationRelatedUser
	 * @dontvalidate $notificationRelatedUser
	 * @return string
	 */
	public function removeMobilizedCommunityUserAction(CommunityUser $notificationRelatedUser) {
		$communityUser = $this->getLoggedInUser();
		if ($communityUser instanceof CommunityUser) {
			// user is logged in
			if ($notificationRelatedUser->getCommunityUser()->getUid() === $communityUser->getUid()) {
				// is it really a child of the logged in user
				// TODO in future: Check is user has changed account to a real account and prevent deletion
				$this->communityUserRepository->remove($notificationRelatedUser);
				$this->persistenceManager->persistAll();
				return json_encode(LocalizationUtility::translate('editMobilizations.removed', 'easyvote'));
			} else {
				return json_encode(LocalizationUtility::translate('editMobilizations.permissionError', 'easyvote'));
			}
		} else {
			return json_encode(LocalizationUtility::translate('editMobilizations.notAuthenticated', 'easyvote'));
		}
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
					} elseif (in_array($this->settings['notificationRelatedUserGroupUid'], $userGroupArray)) {
						// Vote-Wecker only: Remove user, inform parent
						$lazyLoadingFix = $communityUser->getCommunityUser()->getFirstName();
						if ($communityUser->getCommunityUser() instanceof \Visol\Easyvote\Domain\Model\CommunityUser) {
							// Parent user found, so inform him
							/** @var \Visol\Easyvote\Domain\Model\MessagingJob $messagingJob */
							$standaloneView = $this->objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
							$standaloneView->setFormat('html');
							$templatePathAndFilename = $this->resolveViewFileForStandaloneView('Template', 'Email/MobilizedUnsubscribedNotification.html');
							$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
							$standaloneView->assign('parentUser', $communityUser->getCommunityUser());
							$standaloneView->assign('mobilizedUser', $communityUser);
							/** @var \Visol\Easyvote\Domain\Model\VotingDay $nextVotingDay */
							$nextVotingDay = $this->votingDayRepository->findNextVotingDay();
							$standaloneView->assign('nextVotingDay', $nextVotingDay);
							$content = $standaloneView->render();
							$messagingJob = $this->objectManager->get('Visol\Easyvote\Domain\Model\MessagingJob');
							$messagingJob->setContent($content);
							$messagingJob->setSubject($communityUser->getFirstName() . ' ' . LocalizationUtility::translate('mobilizedUnsubscribedNotification.subject', 'easyvote'));
							$messagingJob->setCommunityUser($communityUser->getCommunityUser());
							$messagingJob->setDistributionTime(new \DateTime());
							$messagingJob->setType($messagingJob::JOBTYPE_EMAIL);
							$this->messagingJobRepository->add($messagingJob);
						}
						$this->communityUserRepository->remove($communityUser);
						$this->persistenceManager->persistAll();
						$message = LocalizationUtility::translate('unsubscribe.mobilizedCommunityUserDeleted', 'easyvote');
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
		$languages = array(
			'Deutsch' => CommunityUser::USERLANGUAGE_GERMAN,
			'Französisch' => CommunityUser::USERLANGUAGE_FRENCH,
			'Italienisch' => CommunityUser::USERLANGUAGE_ITALIAN
		);

		$kantons = $this->kantonRepository->findAll();

		$dateTime = new \DateTime();
		$dateTime = $dateTime->format('Y-m-d\TH:i:s');

		$this->view->assignMultiple(array(
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
				$kanton = is_object($communityUser->getKanton()) ? $communityUser->getKanton()->getName() : '';

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
			$this->flashMessageContainer->add('Fehler: Keine Filter-Anfrage für Versand.', '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
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
			$this->flashMessageContainer->add('Test-SMS wurde in Warteschlange gestellt.');
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
				$this->flashMessageContainer->add('Es wurden ' . $communityUsersCount . ' SMS in die Warteschlange gestellt.');
			} else {
				$this->flashMessageContainer->add('Fehler: Keine Filter-Anfrage für Versand.', '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
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

		// generate optin uri
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

		// send optin mail
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
		$communityUser = $this->getLoggedInUser();

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
		$communityUser = $this->getLoggedInUser();

		if ($communityUser instanceof CommunityUser) {
			$appConfiguration = array();
			$appConfiguration['results']['token'] = $communityUser->getAuthToken();
			$appConfiguration['results']['PushChannels'] = array();
			$appConfiguration['results']['PushChannels']['Channel1'] = 'userid_' . $communityUser->getUid();
			$i = 2;
			foreach ($this->settings['pushChannels'] as $pushChannel) {
				$appConfiguration['results']['PushChannels']['Channel' . $i] = $pushChannel;
				$i++;
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
?>