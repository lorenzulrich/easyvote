<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

/* Votings Dashboard */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Currentvotings',
	array(
		'VotingDay' => 'showCurrentVotingDay',
		
	),
	// non-cacheable actions
	array(
	)
);

/* Navigation of all Kantons */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Kantonnavigation',
	array(
		'Kanton' => 'kantonNavigation',
	),
	// non-cacheable actions
	array(
	)
);

/* Voting proposals archive */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Archive',
	array(
		'MetaVotingProposal' => 'archive',
	),
	// non-cacheable actions
	array(
		'MetaVotingProposal' => 'archive',
	)
);

?>