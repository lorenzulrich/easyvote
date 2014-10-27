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

/* AJAX functions for community plugins */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'CommunityAjax',
	array(
		'VotingProposal' => 'showPollForVotingProposal,undoUserVoteForVotingProposal,voteForVotingProposal',
		'CommunityUser' => 'listMobilizedCommunityUsers,newMobilizedCommunityUser,createMobilizedCommunityUser,removeMobilizedCommunityUser',
		'City' => 'listCitiesByPostalCode'
	),
	// non-cacheable actions
	array(
		'VotingProposal' => 'showPollForVotingProposal,undoUserVoteForVotingProposal,voteForVotingProposal',
		'CommunityUser' => 'listMobilizedCommunityUsers,newMobilizedCommunityUser,createMobilizedCommunityUser,removeMobilizedCommunityUser',
	)
);

/* Community-Plugins */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Community',
	array(
		'CommunityUser' => 'userOverview,userFunctions,loginPanel,noProfileNotification,notAuthenticatedModal,profilePicture,create,activate,dataCompletionRequest',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'userOverview,userFunctions,loginPanel,noProfileNotification,notAuthenticatedModal,profilePicture,create,activate,dataCompletionRequest',
	)
);

/* Edit Profile */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Profile',
	array(
		'CommunityUser' => 'editProfile,updateProfile,removeProfile',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'editProfile,updateProfile,removeProfile',
	)
);

/* Eigener Vote-Wecker */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Notifications',
	array(
		'CommunityUser' => 'editNotifications,updateNotifications',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'editNotifications,updateNotifications',
	)
);

/* Freunde mobilisieren */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Mobilize',
	array(
		'CommunityUser' => 'editMobilizations',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'editMobilizations',
	)
);

/* Unsubscribe */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Unsubscribe',
	array(
		'CommunityUser' => 'unsubscribeFromNotification',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'unsubscribeFromNotification',
	)
);

/* Command Controllers */
if (TYPO3_MODE === 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Visol\\Easyvote\\Command\\SmsMessageProcessorCommandController';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Visol\\Easyvote\\Command\\EmailMessageProcessorCommandController';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Visol\\Easyvote\\Command\\ImportCommandController';
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Visol\\Easyvote\\Property\\TypeConverter\\UploadedFileReferenceConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Visol\\Easyvote\\Property\\TypeConverter\\ObjectStorageConverter');

?>