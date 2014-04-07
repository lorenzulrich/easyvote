<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

/* Votings Dashboard */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Currentvotings',
	'Aktuelle Abstimmungen',
	'EXT:easyvote/ext_icon.gif'
);

/* Navigation of all Kantons */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Kantonnavigation',
	'Kantons-Navigation',
	'EXT:easyvote/ext_icon.gif'
);

/* Voting proposals archive */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Archive',
	'Abstimmungs-Archiv',
	'EXT:easyvote/ext_icon.gif'
);

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

$tempColumns = array (
	'tx_easyvote_contentclass' => array (
		'exclude' => 1,
		'label'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_contentclass',
		'config' => array (
			'type' => 'input',
			'size' => '30',
		)
	),
);

# Content Element for displaying an image from image field or CSS class
\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('tt_content');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content',$tempColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content','tx_easyvote_contentclass;;;;1-1-1');

$tempColumns = array (
	'tx_easyvote_pageclass' => array (
		'exclude' => 1,
		'label'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_pageclass',
		'config' => array (
			'type' => 'input',
			'size' => '30',
		)
	),
);

# Content Element for displaying an image from image field or CSS class
\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('pages');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tempColumns,1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages','tx_easyvote_pageclass;;;;1-1-1', '', 'after:backend_layout_next_level');

$TCA['tt_content']['columns']['CType']['config']['easyvoteimage'] = array(
	'label' => 'easyvote Bild',
	//'config' => array(...)
);

$TCA['tt_content']['columns']['CType']['config']['items'][] = array('easyvote Bild', 'easyvoteimage', 'EXT:easyvote/ext_icon.gif');

$TCA['tt_content']['types']['easyvoteimage'] = array(
	'showitem' => 'CType;;4;;1-1-1, hidden, tx_easyvote_contentclass, image, layout,
                    --div--;LLL:EXT:cms/locallang_tca.xml:pages.tabs.access, starttime, endtime'
);


/* Frontend User Integration */
$TCA['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser','Tx_Easyvote_CommunityUser');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $TCA['fe_users']['ctrl']['type'],'','after:hidden');


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
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $communityUserColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $TCA['fe_users']['ctrl']['type'],'','after:hidden');

$TCA['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',--div--;LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser';
$TCA['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',gender, kanton, user_language, birthdate';

?>