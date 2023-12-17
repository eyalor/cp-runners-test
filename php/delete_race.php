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
	$sql = "SELECT COUNT(*) AS no_results from tl_races_data WHERE tl_races_data.race_id='" . $race->race_id . "'";
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
	if (intval($result[0]['no_results']) > 0) {
		die(getErrorStatusWithDummyData("Race could not be deleted. Results are attached!"));
	}
	$sql = 'DELETE FROM tl_races WHERE id=:id';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':id' => $race->race_id
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to delete race."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}
