<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

/* Frontend User Integration */
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$GLOBALS['TCA']['fe_users']['columns'][$GLOBALS['TCA']['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser', 'Tx_Easyvote_CommunityUser');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type']);


/* New columns for FrontendUser of type CommunityUser */
$communityUserColumns = [
    'gender' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender',
        'config' => [
            'type' => 'radio',
            'default' => 2,
            'items' => [
                ['LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender.m', 1],
                ['LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.gender.f', 2]
            ]
        ]
    ],
    'user_language' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language',
        'config' => [
            'type' => 'select',
            'items' => [
                ['LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.german', 1],
                ['LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.french', 2],
                ['LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.user_language.italian', 3],
            ],
        ],
    ],
    'kanton' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_metavotingproposal.kanton',
        'config' => [
            'type' => 'select',
            'items' => [
                ['Kanton wählen...', 0]
            ],
            'foreign_table' => 'tx_easyvote_domain_model_kanton',
            'foreign_table_where' => 'ORDER BY tx_easyvote_domain_model_kanton.name',
            'minitems' => 1,
            'maxitems' => 1,
        ],
    ],
    'birthdate' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.birthdate',
        'config' => [
            'type' => 'input',
            'eval' => 'date',
            'size' => '8',
            'max' => '20'
        ]
    ],
    'auth_token' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.auth_token',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'notification_mail_active' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.notification_mail_active',
        'config' => [
            'type' => 'check',
            'default' => 0
        ],
    ],
    'community_news_mail_active' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.community_news_mail_active',
        'config' => [
            'type' => 'check',
            'default' => 0
        ],
    ],
    'notification_sms_active' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.notification_sms_active',
        'config' => [
            'type' => 'check',
            'default' => 0
        ],
    ],
    'followers' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.followers',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'fe_users',
            'foreign_field' => 'community_user',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
            ],
        ],
    ],
    'community_user' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.community_user',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'fe_users',
            'readOnly' => 1,
            'items' => [
                ['', ''],
            ],
        ],
    ],
    'city_selection' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.city_selection',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'tx_easyvote_domain_model_city',
            'items' => [
                ['', ''],
            ],
        ],
    ],
    'party' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.party',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'tx_easyvote_domain_model_party',
            'foreign_table_where' => ' AND tx_easyvote_domain_model_party.sys_language_uid IN (-1,0) ORDER BY short_title ASC, title ASC',
            'items' => [
                ['', ''],
            ],
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],
    'party_verification_code' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.party_verification_code',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'fal_image' => [
        'exclude' => 1,
        'label' => 'FAL Image',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('fal_image', [
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
            ],
            'foreign_match_fields' => [
                'fieldname' => 'falimage',
                'tablenames' => 'fe_users',
                'table_local' => 'sys_file',
            ],
            'minitems' => 0,
            'maxitems' => 1,
        ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
    ],
    'events' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.events',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_easyvote_domain_model_event',
            'foreign_field' => 'community_user',
            'maxitems' => 1,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
            ],
        ],
    ],
    'tx_easyvoteeducation_panels' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.tx_easyvoteeducation_panels',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_easyvoteeducation_domain_model_panel',
            'foreign_field' => 'community_user',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
            ],
        ],
    ],
    'personal_election_lists' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.personal_election_lists',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_easyvotesmartvote_domain_model_personalelectionlist',
            'foreign_field' => 'community_user',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
            ],
        ],
    ],
    'privacy_protection' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.privacy_protection',
        'config' => [
            'type' => 'check',
            'default' => 0
        ],
    ],
    'organization' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'organization_website' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization_website',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'organization_city' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.organization_city',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'tx_easyvote_domain_model_city',
            'items' => [
                ['', ''],
            ],
        ],
    ],
    'education_type' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.education_type',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'education_institution' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.education_institution',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],
    'vip' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.vip',
        'config' => [
            'type' => 'check',
            'default' => 0
        ],
    ],
    'cached_follower_rank_ch' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.cached_follower_rank_ch',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'int,trim'
        ],
    ],
    'cached_follower_rank_canton' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.cached_follower_rank_canton',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'int,trim'
        ],
    ],
    'cached_follower_rank_vip' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.cached_follower_rank_vip',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'int,trim'
        ],
    ],
    'party_admin_allowed_cantons' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser.party_admin_allowed_cantons',
        'config' => [
            'type' => 'select',
            'foreign_table' => 'tx_easyvote_domain_model_kanton',
            'foreign_table_where' => ' AND tx_easyvote_domain_model_kanton.sys_language_uid IN (-1,0) ORDER BY name ASC',
            'MM' => 'tx_easyvote_feuser_kanton_mm',
            'size' => 10,
            'autoSizeMax' => 30,
            'maxitems' => 9999,
            'multiple' => 0,
            'enableMultiSelectFilterTextfield' => true,
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $communityUserColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('fe_users', $GLOBALS['TCA']['fe_users']['ctrl']['type']);

$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] = $GLOBALS['TCA']['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .= ',--div--;LLL:EXT:easyvote/Resources/Private/Language/locallang_db.xlf:tx_easyvote_domain_model_communityuser';
$GLOBALS['TCA']['fe_users']['types']['Tx_Easyvote_CommunityUser']['showitem'] .=
    ',gender, privacy_protection, city_selection, kanton, party, party_verification_code, party_admin_allowed_cantons, user_language, birthdate, fal_image, auth_token, notification_mail_active, notification_sms_active,community_news_mail_active,followers, events, community_user, tx_easyvoteeducation_panels, organization, organization_website, organization_city, education_type, education_institution, personal_election_lists, vip, cached_follower_rank_ch, cached_follower_rank_canton, cached_follower_rank_vip';

$GLOBALS['TCA']['fe_users']['ctrl']['label_alt'] = 'last_name,first_name';
$GLOBALS['TCA']['fe_users']['ctrl']['label_alt_force'] = TRUE;
