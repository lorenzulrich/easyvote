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
		'CommunityUser' => 'userOverview,userFunctions,loginPanel',
	),
	// non-cacheable actions
	array(
		'CommunityUser' => 'userOverview,userFunctions,loginPanel',
	)
);

/* Community-Plugins */
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

# Content Element for displaying an image from image field or CSS class
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
mod.wizards.newContentElement.wizardItems {
    easyvoteimage {
        icon = EXT:easyvote/ext_icon.gif
    }
    common.show := addToList(easyvoteimage)
}

mod.wizards.newContentElement.wizardItems.common.elements.easyvoteimage {
		icon = ../typo3conf/ext/easyvote/ext_icon.gif
        title = easyvote Bild
        description = Zeigt ein Bild in einer quadratischen Box an
        tt_content_defValues {
			CType = easyvoteimage
        }
}

');

/* Command Controllers */
if (TYPO3_MODE === 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Visol\\Easyvote\\Command\\SmsMessageProcessorCommandController';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Visol\\Easyvote\\Command\\EmailMessageProcessorCommandController';
}

?>