<?php

/*
 * Created on May 19, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'constants.php';
require_once 'utils.php';
require_once 'DBLogger.php';

function getUser() {
	if (!isset ($_GET['userStr'])) {
		die(getErrorStatusWithDummyData("Mandatory input - userStr was not found."));
	}
	$userStr = $_GET['userStr'];
	$user = json_decode($userStr);
	$json_decode_error = json_last_error();
	if ($json_decode_error != JSON_ERROR_NONE) {
		die(getErrorStatusWithDummyData("Failed to decode JSON - " . getJSONDEcodeErrDesc($json_decode_error)));
	}
	return $user;

}
?>

