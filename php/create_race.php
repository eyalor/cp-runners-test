<?php

/*
 * Created on May 18, 2012
 *
 * Create a new user shoe
 * 
 * http://www.bc-running.com/runlog/php/create_shoe.php?shoeStr={"name" : "Puma3","type" : 2, "runner_id" : 12345, "start_using_date" : 123134434 }
 * 
 * TODO: create a new table with the shoe types + update the tl_shoes table  - add the shoe type
 * 
 * 
 */
 
require_once 'ajax_page_init.php';
require_once 'race_common.php';


$race = getRace();

try {
	$conn = getConnection();
	$sql = 'INSERT INTO tl_races (race_name,type_id,race_date,race_notes) VALUES (:race_name,:type_id,:race_date,:race_notes)';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':race_name' => $race->race_name,
		':type_id' => $race->race_type,
		':race_date' => $race->race_date,
		':race_notes' => $race->race_notes
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to Create a race."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}


