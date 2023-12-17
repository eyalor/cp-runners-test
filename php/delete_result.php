<?php
/*
 * Created on May 19, 2012
 *
 * Update existing user shoe - fields that can be changed: name,type,start_using_date
 */
require_once 'ajax_page_init.php';
require_once 'result_common.php';

//
// TODO: make sure the shoe that we try to update belongs to the user
//

//$runner_id = $memberAuthentication->getMemberId();
$result = getResult();

try {
	$conn = getConnection();
	$sql = 'delete from tl_races_data WHERE runner_id=:runner_id and race_id=:race_id';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':runner_id' => $result->runner_id,
		':race_id' => $result->race_id
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to update result."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}
