<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['tx_easyvote_domain_model_messagingjob'] = [
    'ctrl' => [
        'title' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob',
        'label' => 'community_user',
        'label_alt' => 'subject',
        'label_alt_force' => TRUE,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'sortby' => 'distribution_time',

        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',

        'delete' => 'deleted',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('easyvote') . 'Resources/Public/Icons/tx_easyvote_domain_model_messagingjob.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'type, community_user, recipient_name, recipient_email, sender_name, sender_email, return_path, reply_to, subject, content, distribution_time, time_distributed, time_error, error_code, processor_response'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.type',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['SMS', 1],
                    ['E-Mail', 2],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
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
        'subject' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.subject',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'recipient_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.recipient_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'recipient_email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.recipient_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'sender_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.sender_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'sender_email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.sender_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'return_path' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.return_path',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'reply_to' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.reply_to',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'content' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.content',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'distribution_time' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.distribution_time',
            'config' => [
                'type' => 'input',
                'size' => 14,
                'eval' => 'datetime,required',
                'checkbox' => 1,
            ],
        ],
        'time_distributed' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.time_distributed',
            'config' => [
                'type' => 'input',
                'size' => 14,
                'eval' => 'datetime',
                'checkbox' => 1,
            ],
        ],
        'time_error' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.time_error',
            'config' => [
                'type' => 'input',
                'size' => 14,
                'eval' => 'datetime',
                'checkbox' => 1,
            ],
        ],
        'error_code' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.error_code',
            'config' => [
                'type' => 'input',
                'size' => 14,
                'eval' => 'trim,int'
            ],
        ],
        'processor_response' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_messagingjob.processor_response',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
    ],
];

?>