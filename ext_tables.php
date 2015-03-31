<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

/* Votings Dashboard */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Currentvotings',
	'easyvote Abstimmungen: Aktuelle Abstimmungen',
	'EXT:easyvote/ext_icon.gif'
);

/* Navigation of all Kantons */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Kantonnavigation',
	'easyvote Abstimmungen/Wahlen: Kantons-Navigation',
	'EXT:easyvote/ext_icon.gif'
);

/* Voting proposals archive */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Archive',
	'easyvote Abstimmungen: Archiv',
	'EXT:easyvote/ext_icon.gif'
);

/* AJAX functions for community plugins */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'CommunityAjax',
	'easyvote Community: AJAX-Funktionen',
	'EXT:easyvote/ext_icon.gif'
);

/* Community-Plugins */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Community',
	'easyvote Community: Widgets',
	'EXT:easyvote/ext_icon.gif'
);

$pluginSignature = str_replace('_','',$_EXTKEY) . '_community';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForm/flexform_community.xml');

/* Profil bearbeiten */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Profile',
	'easyvote Community: Profil bearbeiten',
	'EXT:easyvote/ext_icon.gif'
);

/* Eigener Vote-Wecker */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Notifications',
	'easyvote Community: Eigener Vote-Wecker',
	'EXT:easyvote/ext_icon.gif'
);

/* Freunde mobilisieren */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Mobilize',
	'easyvote Community: Freunde mobilisieren',
	'EXT:easyvote/ext_icon.gif'
);

/* Unsubscribe */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Unsubscribe',
	'easyvote Community: Vote-Wecker abmelden',
	'EXT:easyvote/ext_icon.gif'
);

/* Easyvote Backend Module */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
	'Visol.' . $_EXTKEY,
	'user',
	'easyvote',
	'top',
	array(
		'CommunityUser' => 'backendDashboard,backendSmsMessagingIndex,backendSmsMessageSend,backendEmailExportIndex,backendEmailExportPerform'
	),
	array(
		'access' => 'user,group',
		'icon' => 'EXT:easyvote/ext_icon.gif',
		'labels' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_module.xlf'
	)
);

/* TypoScript-Konfiguration */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'easyvote');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_metavotingproposal', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_metavotingproposal.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_metavotingproposal');
$TCA['tx_easyvote_domain_model_metavotingproposal'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal',
		'label' => 'private_title',
		'type' => 'type',
		'requestUpdate' => 'scope',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'private_title,type,scope,main_proposal_approval,voting_proposals,kanton,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/MetaVotingProposal.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_metavotingproposal.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_votingproposal', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_votingproposal.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_votingproposal');
$TCA['tx_easyvote_domain_model_votingproposal'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_votingproposal',
		'label' => 'short_title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'short_title,official_title,youtube_url,goal,initial_status,consequence,pro_arguments,contra_arguments,government_opinion,links,proposal_approval,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/VotingProposal.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_votingproposal.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_poll');
$TCA['tx_easyvote_domain_model_poll'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_poll',
		'label' => 'value',
		'label_alt' => 'voting_proposal, community_user',
		'label_alt_force' => TRUE,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'crdate',
		'delete' => 'deleted',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Poll.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_poll.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_kanton', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_kanton.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_kanton');
$TCA['tx_easyvote_domain_model_kanton'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_kanton',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'sortby' => 'name',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'name,abbreviation,cities,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Kanton.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_kanton.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_votingday', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_votingday.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_votingday');
$TCA['tx_easyvote_domain_model_votingday'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_votingday',
		'label' => 'voting_date',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'voting_date,archived,meta_voting_proposals,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/VotingDay.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_votingday.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_city', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_city.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_city');
$TCA['tx_easyvote_domain_model_city'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_city',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'label' => 'name',
		'label_alt' => 'postal_code, municipality',
		'label_alt_force' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'name,postal_code,municipality,longitude,latitude,kanton,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/City.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_city.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_easyvote_domain_model_language', 'EXT:easyvote/Resources/Private/Language/locallang_csh_tx_easyvote_domain_model_language.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_language');
$TCA['tx_easyvote_domain_model_language'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_language',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'zip,name,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Language.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_language.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_messagingjob');
$TCA['tx_easyvote_domain_model_messagingjob'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob',
		'label' => 'community_user',
		'label_alt' => 'subject',
		'label_alt_force' => TRUE,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'distribution_time',

		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'delete' => 'deleted',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/MessagingJob.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_easyvote_domain_model_messagingjob.gif'
	),
);
