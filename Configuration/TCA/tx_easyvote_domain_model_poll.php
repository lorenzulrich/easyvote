<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['tx_easyvote_domain_model_poll'] = [
    'ctrl' => [
        'title' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_poll',
        'label' => 'value',
        'label_alt' => 'voting_proposal, community_user',
        'label_alt_force' => TRUE,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'sortby' => 'crdate',
        'delete' => 'deleted',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('easyvote') . 'Resources/Public/Icons/tx_easyvote_domain_model_poll.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'value, voting_proposal, community_user'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'value' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_poll.value',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['ja', 1],
                    ['nein', 2],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'voting_proposal' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_votingproposal',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_easyvote_domain_model_votingproposal',
                'foreign_table_where' => 'AND tx_easyvote_domain_model_votingproposal.pid=###CURRENT_PID### AND tx_easyvote_domain_model_votingproposal.sys_language_uid IN (-1,0) ORDER BY tx_easyvote_domain_model_votingproposal.short_title',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'community_user' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'fe_users',
                'foreign_table_where' => 'AND tx_extbase_type=\'Tx_Easyvote_CommunityUser\'',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
    ],
];

?>