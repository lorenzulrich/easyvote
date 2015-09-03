<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_easyvote_domain_model_event'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event',
		'label' => 'community_user',
		'label_alt' => 'location,date',
		'label_alt_force' => TRUE,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

//		'languageField' => 'sys_language_uid',
//		'transOrigPointerField' => 'l10n_parent',
//		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'date, from_time, comment, location, community_user',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('easyvote') . 'Resources/Public/Icons/tx_easyvote_domain_model_event.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, date, from_time, comment, location, community_user',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, date, from_time, comment, location, community_user'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
//		'sys_language_uid' => array(
//			'exclude' => 1,
//			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
//			'config' => array(
//				'type' => 'select',
//				'foreign_table' => 'sys_language',
//				'foreign_table_where' => 'ORDER BY sys_language.title',
//				'items' => array(
//					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
//					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
//				),
//			),
//		),
//		'l10n_parent' => array(
//			'displayCond' => 'FIELD:sys_language_uid:>:0',
//			'exclude' => 1,
//			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
//			'config' => array(
//				'type' => 'select',
//				'items' => array(
//					array('', 0),
//				),
//				'foreign_table' => 'tx_easyvote_domain_model_event',
//				'foreign_table_where' => 'AND tx_easyvote_domain_model_event.pid=###CURRENT_PID### AND tx_easyvote_domain_model_event.sys_language_uid IN (-1,0)',
//			),
//		),
//		'l10n_diffsource' => array(
//			'config' => array(
//				'type' => 'passthrough',
//			),
//		),

		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'comment' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event.comment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'date' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event.date',
			'config' => array(
				'dbType' => 'date',
				'type' => 'input',
				'size' => 12,
				'eval' => 'date',
				'checkbox' => 0,
				'default' => '0000-00-00'
			),
		),
		'from_time' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event.from_time',
			'config' => array(
				'type' => 'input',
				'size' => 12,
				'eval' => 'time',
				'checkbox' => 0,
			),
		),
		'community_user' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event.community_user',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				//'readOnly' => 1,
				'items'   => array(
					array('', ''),
				),
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
		'location' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_event.location',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_easyvotelocation_domain_model_location',
				//'readOnly' => 1,
				'items'   => array(
					array('', ''),
				),
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
	),
);
