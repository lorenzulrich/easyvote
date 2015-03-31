<?php
if (!defined('TYPO3_MODE')) { die ('Access denied.'); }

/* Frontend User Integration */
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$GLOBALS['TCA']['fe_users']['columns'][$GLOBALS['TCA']['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser','Tx_Easyvote_CommunityUser');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type']);


/* New columns for FrontendUser of type CommunityUser */
$communityUserColumns = array(
	'gender' => array (
		'exclude' => 1,
		'label'  => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender',
		'config' => array (
			'type'    => 'radio',
			'default' => 2,
			'items'   => array(
				array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender.m', 1),
				array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender.f', 2)
			)
		)
	),
	'user_language' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.german', 1),
				array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.french', 2),
				array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.italian', 3),
			),
		),
	),
	'kanton' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.kanton',
		'config' => array(
			'type' => 'select',
			'items' => array(
				array('Kanton wählen...', 0)
			),
			'foreign_table' => 'tx_easyvote_domain_model_kanton',
			'foreign_table_where' => 'ORDER BY tx_easyvote_domain_model_kanton.name',
			'minitems' => 1,
			'maxitems' => 1,
		),
	),
	'birthdate' => array (
		'exclude' => 1,
		'label'   => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.birthdate',
		'config'  => array (
			'type' => 'input',
			'eval' => 'date',
			'size' => '8',
			'max'  => '20'
		)
	),
	'auth_token' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.auth_token',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
	'notification_mail_active' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.notification_mail_active',
		'config' => array(
			'type' => 'check',
			'default' => 0
		),
	),
	'notification_sms_active' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.notification_sms_active',
		'config' => array(
			'type' => 'check',
			'default' => 0
		),
	),
	'notification_related_users' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.notification_related_users',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'fe_users',
			'foreign_field' => 'community_user',
			'maxitems'      => 9999,
			'appearance' => array(
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
			),
		),
	),
	'community_user' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.community_user',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'fe_users',
			'readOnly' => 1,
			'items'   => array(
				array('', ''),
			),
		),
	),
	'city_selection' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.city_selection',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_easyvote_domain_model_city',
			'items'   => array(
				array('', ''),
			),
		),
	),
	'fal_image' => array(
		'exclude' => 1,
		'label' => 'FAL Image',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('fal_image', array(
			'appearance' => array(
				'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
			),
			'foreign_match_fields' => array(
				'fieldname' => 'falimage',
				'tablenames' => 'fe_users',
				'table_local' => 'sys_file',
			),
			'minitems' => 0,
			'maxitems' => 1,
		), $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
	),
	'tx_easyvoteeducation_panels' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.tx_easyvoteeducation_panels',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_easyvoteeducation_domain_model_panel',
			'foreign_field' => 'community_user',
			'maxitems'      => 9999,
			'appearance' => array(
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
			),
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $communityUserColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type']);

$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',--div--;LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser';
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',gender, city_selection, kanton, user_language, birthdate, fal_image, auth_token, notification_mail_active, notification_sms_active,notification_related_users, community_user, tx_easyvoteeducation_panels';

$GLOBALS['TCA']['fe_users']['ctrl']['label_alt'] = 'last_name,first_name';
$GLOBALS['TCA']['fe_users']['ctrl']['label_alt_force'] = TRUE;