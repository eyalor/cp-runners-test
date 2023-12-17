<?php

// -----------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------
header('Access-Control-Allow-Origin: *');
require_once 'member_authentication.php';
require_once 'ValidationResult.php';
require_once 'event_common.php';
$json = file_get_contents('php://input');
//echo $json;
$eventFields = json_decode($json, true);


$conn = getConnection();
for($i=0; $i<sizeof($eventFields); $i++) {
    $strava_id = $eventFields[$i]['upload_id'];
    try {
        $sql = "SELECT * FROM tl_events WHERE strava_id = '" . $strava_id . "'";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
    }
    
    catch (PDOException $e) {
        die(getErrorStatusWithDummyData($e->getMessage()));
    }
    echo $result[0]->strava_id . "\n";
    if ($result) {
        $eventFields[$i]["loaded"] ="true";
    }
    else{
        $eventFields[$i]["loaded"] ="false";
    }
}
$conn = null;
 echo returnJSONsuccess($eventFields);
 
