<?php

// -----------------------------------------------------------------------------------------
// This script is responsible for creating a new event for a user.
// It expects 2 input parameters:
// 1)  The member ID - in order to see if the caller is logged in
// 2)  The Date - for setting the event's date
// 3)  The event type ID
// 4)  The warmup time
// 5)  The main excersize time
// 6)  The cooldown time
// 7)  The warmup distance
// 8)  The main excersize distance
// 9)  The cooldown distance
// 10) The event description
// 12) The shoe ID
// 13) The course ID
// The output of the script is a JSON with 2 fields:
// 1) ecode [ERR || OK]
// 2) emessage - free text
// NOTE: since the client is expecting a JSON result - we should always return a valid JSON
// -----------------------------------------------------------------------------------------
echo "11111";
//require_once 'ajax_page_init.php';
require_once 'ValidationResult.php';
require_once 'event_common.php';

//$eventFields = getEventFields();
$eventFields = json_decode($_POST);
$json = file_get_contents('php://input');

$data = json_decode($json);
  echo $data[0]->firstname;
  $conn = getConnection();
//$conn = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DBNAME);
try {
	$sql = 'INSERT INTO strava (first_name,last_name) VALUES (:firstname,:lastname)';
	echo $sql;
	$sth = $conn->prepare($sql);

	$ok = $sth->execute(array (
		':firstname' => $data[0]->firstname,
		':lastname' => $data[0]->lastname
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to Create a shoe."));
	} else {
	    
		echo returnJSONsuccess("sssss");
	}
	$conn = null;
} 
catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}