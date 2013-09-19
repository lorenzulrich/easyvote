<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Currentvotings',
	array(
		'VotingDay' => 'showCurrentVotingDay',
		
	),
	// non-cacheable actions
	array(
		'MetaVotingProposal' => '',
		'VotingProposal' => '',
		'Kanton' => '',
		'VotingDay' => '',
		'City' => '',
	)
);

?>