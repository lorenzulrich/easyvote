<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_easyvote_contentclass' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:easyvote/locallang_db.xml:tt_content.tx_easyvote_contentclass',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_easyvote_contentclass;;;;1-1-1');
?>