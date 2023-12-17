<?php

/*
 * Created on May 18, 2012
 *
 * Create a new user shoe
 * 
 * http://www.bc-running.com/runlog/php/create_shoe.php?shoeStr={"name" : "Puma3","type" : 2, "runner_id" : 12345, "start_using_date" : 123134434 }
 * 
 * 
 */
 
require_once 'ajax_page_init.php';
require_once 'constants.php';
require_once 'utils.php';
require_once 'DBLogger.php';

if (!isset ($_GET['resultStr'])) {
	die(getErrorStatusWithDummyData("Mandatory input - resultStr was not found."));
}
$resultStr = $_GET['resultStr'];
$result = json_decode($resultStr);
$json_decode_error = json_last_error();
if ($json_decode_error != JSON_ERROR_NONE) {
	die(getErrorStatusWithDummyData("Failed to decode JSON - " . getJSONDEcodeErrDesc($json_decode_error)));
}

try {
	$conn = getConnection();
	$sql = 'INSERT INTO tl_races_data (runner_id,race_id,run_time,notes) VALUES (:runner_id, :race_id,:run_time,:notes)';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':runner_id' => $result->runner_id,
		':race_id' => $result->race_id,
		':run_time' => $result->run_time,
		':notes' => $result->notes
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to Create a result."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}

?>
