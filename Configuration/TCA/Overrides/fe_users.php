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
	'followers' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.followers',
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
	'party' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.party',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_easyvote_domain_model_party',
			'foreign_table_where' => ' AND tx_easyvote_domain_model_party.sys_language_uid IN (-1,0) ORDER BY short_title ASC, title ASC',
			'items'   => array(
				array('', ''),
			),
			'minitems' => 0,
			'maxitems' => 1,
		),
	),
	'party_verification_code' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.party_verification_code',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
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
	'events' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.events',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_easyvote_domain_model_event',
			'foreign_field' => 'community_user',
			'maxitems'      => 1,
			'appearance' => array(
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
			),
		),
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
	'personal_election_lists' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.personal_election_lists',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_easyvotesmartvote_domain_model_personalelectionlist',
			'foreign_field' => 'community_user',
			'maxitems'      => 9999,
			'appearance' => array(
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
			),
		),
	),
	'privacy_protection' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.privacy_protection',
		'config' => array(
			'type' => 'check',
			'default' => 0
		),
	),
	'organization' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
	'organization_website' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization_website',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
	'organization_city' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization_city',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_easyvote_domain_model_city',
			'items'   => array(
				array('', ''),
			),
		),
	),
	'education_type' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.education_type',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
	'education_institution' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.education_institution',
		'config' => array(
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $communityUserColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type']);

$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',--div--;LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser';
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .=
	',gender, privacy_protection, city_selection, kanton, party, party_verification_code, user_language, birthdate, fal_image, auth_token, notification_mail_active, notification_sms_active,followers, events, community_user, tx_easyvoteeducation_panels, organization, organization_website, organization_city, education_type, education_institution, personal_election_lists';

$GLOBALS['TCA']['fe_users']['ctrl']['label_alt'] = 'last_name,first_name';
$GLOBALS['TCA']['fe_users']['ctrl']['label_alt_force'] = TRUE;

// Exclude more fields from TCA.
$tca = array(
	'grid' => array(
		'excluded_fields' => $GLOBALS['TCA']['fe_users']['grid']['excluded_fields'] . ', fal_image',
	),
);

\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TCA']['fe_users'], $tca);