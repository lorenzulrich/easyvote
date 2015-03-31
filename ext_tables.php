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

/* Allow all tables on standard pages */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_metavotingproposal');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_votingproposal');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_poll');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_kanton');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_votingday');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_city');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_language');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_easyvote_domain_model_messagingjob');
