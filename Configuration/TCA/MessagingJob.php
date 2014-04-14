<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_easyvote_domain_model_messagingjob'] = array(
	'ctrl' => $TCA['tx_easyvote_domain_model_messagingjob']['ctrl'],
	'types' => array(
		'1' => array('showitem' => 'type, community_user, content, distribution_time, time_distributed, time_error, error_code'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('SMS', 1),
					array('E-Mail', 2),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required'
			),
		),
		'community_user' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_table_where' => 'AND tx_extbase_type=\'Tx_Easyvote_CommunityUser\'',
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
		'content' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
			),
		),
		'distribution_time' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.distribution_time',
			'config' => array(
				'type' => 'input',
				'size' => 14,
				'eval' => 'datetime,required',
				'checkbox' => 1,
			),
		),
		'time_distributed' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.time_distributed',
			'config' => array(
				'type' => 'input',
				'size' => 14,
				'eval' => 'datetime',
				'checkbox' => 1,
			),
		),
		'time_error' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.time_error',
			'config' => array(
				'type' => 'input',
				'size' => 14,
				'eval' => 'datetime',
				'checkbox' => 1,
			),
		),
		'error_code' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.error_code',
			'config' => array(
				'type' => 'input',
				'size' => 14,
				'eval' => 'trim,int'
			),
		),
	),
);

?>