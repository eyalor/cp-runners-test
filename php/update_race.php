<?php
/*
 * Created on May 19, 2012
 *
 * Update existing user shoe - fields that can be changed: name,type,start_using_date
 */
require_once 'ajax_page_init.php';
require_once 'race_common.php';

//
// TODO: make sure the shoe that we try to update belongs to the user
//

$runner_id = $memberAuthentication->getMemberId();
$race = getRace();

try {
	$conn = getConnection();
	$sql = 'UPDATE tl_races SET race_name=:race_name,race_date=:race_date,type_id=:type,race_notes=:race_notes WHERE id=:id';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':id' => $race->race_id,
		':race_name' => $race->race_name,
		':race_date' => $race->race_date,
		':type' => $race->race_type,
		':race_notes' => $race->race_notes
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to update race."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}
