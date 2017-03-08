<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TCA']['tx_easyvote_domain_model_metavotingproposal'] = [
    'ctrl' => [
        'title' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal',
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
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'private_title,type,scope,main_proposal_approval,voting_proposals,kanton,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('easyvote') . 'Resources/Public/Icons/tx_easyvote_domain_model_metavotingproposal.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, private_title, type, scope, main_proposal_approval, voting_proposals, kanton',
    ],
    'types' => [
        '1' => ['showitem' =>
            'sys_language_uid, l10n_parent, l10n_diffsource, hidden, private_title, voting_day, type, scope, voting_proposals, kanton, image,
             --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'],
        '2' => ['showitem' =>
            'sys_language_uid, l10n_parent, l10n_diffsource, hidden, private_title, voting_day, type, scope, main_proposal_approval, voting_proposals, kanton, image,
             --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [

        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_easyvote_domain_model_metavotingproposal',
                'foreign_table_where' => 'AND tx_easyvote_domain_model_metavotingproposal.pid=###CURRENT_PID### AND tx_easyvote_domain_model_metavotingproposal.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ]
        ],

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'private_title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.private_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'voting_day' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_votingday',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_easyvote_domain_model_votingday',
                'MM' => 'tx_easyvote_votingday_metavotingproposal_mm',
                'MM_opposite_field' => 'meta_voting_proposals',
                'size' => 1,
                'maxitems' => 1,
                'multiple' => 0,
                'readOnly' => 1,
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.type',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['normal', 1],
                    ['mit Gegenvorschlag', 2],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'scope' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.scope',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['national', 1],
                    ['kantonal', 2],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => 'required'
            ],
        ],
        'main_proposal_approval' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.main_proposal_approval',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            ],
        ],
        'voting_proposals' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.voting_proposals',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_easyvote_domain_model_votingproposal',
                'foreign_table_where' => ' AND tx_easyvote_domain_model_votingproposal.sys_language_uid IN (-1,0) ORDER BY short_title ASC',
                'MM' => 'tx_easyvote_metavotingproposal_votingproposal_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'enableMultiSelectFilterTextfield' => TRUE,
                'wizards' => [
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'edit' => [
                        'type' => 'popup',
                        'title' => 'Edit',
                        'script' => 'wizard_edit.php',
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                    ],
                    'add' => [
                        'type' => 'script',
                        'title' => 'Create new',
                        'icon' => 'add.gif',
                        'params' => [
                            'table' => 'tx_easyvote_domain_model_votingproposal',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ],
                        'script' => 'wizard_add.php',
                    ],
                ],
            ],
        ],
        'kanton' => [
            'exclude' => 1,
            'displayCond' => 'FIELD:scope:=:2',
            'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.kanton',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_easyvote_domain_model_kanton',
                'foreign_table_where' => 'ORDER BY tx_easyvote_domain_model_kanton.name',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'image' => [
            'exclude' => 1,
            'label' => 'Image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
                ],
                'minitems' => 0,
                'maxitems' => 1,
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ],
    ],
];
