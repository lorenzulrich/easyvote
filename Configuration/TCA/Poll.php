<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_easyvote_domain_model_poll'] = array(
	'ctrl' => $TCA['tx_easyvote_domain_model_poll']['ctrl'],
	'types' => array(
		'1' => array('showitem' => 'value, voting_proposal'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'value' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_poll.value',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('ja', 1),
					array('nein', 2),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => 'required'
			),
		),
		'voting_proposal' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_votingproposal',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_easyvote_domain_model_votingproposal',
				'foreign_table_where' => 'AND tx_easyvote_domain_model_votingproposal.pid=###CURRENT_PID### AND tx_easyvote_domain_model_votingproposal.sys_language_uid IN (-1,0) ORDER BY tx_easyvote_domain_model_votingproposal.short_title',
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
	),
);

?>