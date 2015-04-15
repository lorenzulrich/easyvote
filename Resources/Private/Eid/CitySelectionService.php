<?php
/** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
$TSFE = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
$TSFE->set_no_cache();
$TSFE->connectToDB();

header('Content-Type: application/json');

/** @var \TYPO3\CMS\Core\Database\DatabaseConnection $databaseConnection */
$databaseConnection = $GLOBALS['TYPO3_DB'];
$cityTable = 'tx_easyvote_domain_model_city';
$q = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('q');

$queryString = $databaseConnection->escapeStrForLike($databaseConnection->quoteStr($q, $cityTable), $cityTable);
$where = 'deleted = 0 AND hidden = 0 AND (postal_code LIKE \'' . $q . '%\' OR name LIKE \'' . $q . '%\')';
$cities = $databaseConnection->exec_SELECTgetRows('uid,postal_code,name,kanton,latitude,longitude', $cityTable, $where);

$returnArray['results'] = array();
foreach ($cities as $city) {
	$returnArray['results'][] = array(
		'id' => $city['uid'],
		'text' => $city['postal_code'] . ' ' . $city['name'],
		'postalCode' => $city['postal_code'],
		'city' => $city['name'],
		'kanton' => $city['kanton'],
		'latitude' => $city['latitude'],
		'longitude' => $city['longitude']
	);
}
$returnArray['more'] = FALSE;
ob_flush();
flush();
echo json_encode($returnArray);
