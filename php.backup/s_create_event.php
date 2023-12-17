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
header('Access-Control-Allow-Origin: *');
require_once 'member_authentication.php';
require_once 'ValidationResult.php';
require_once 'event_common.php';
$json = file_get_contents('php://input');
//echo $json;
$eventFields = json_decode($json);
$memberAuthentication = new memberAuthentication();
$the_date = $eventFields->start_date_local;
$avg_pulse = $eventFields->average_heartrate;
$total_elev = $eventFields->total_elevation_gain;
$runner_id = $eventFields->sw_id;
$member_name = $eventFields->member_name;
$strava_id = $eventFields->upload_id;
// OK - now we have a valid input - lets try to create the event from DB
try {
    $conn = getConnection();

    $sql = 'INSERT INTO s_events (run_date,run_time,run_distance,runner_id,member_name,pulse,total_elev,strava_id) VALUES (:run_date,:run_time,:run_distance,:runner_id,:member_name,:pulse,:total_elev,:strava_id)';
    $sth = $conn->prepare($sql);
    $ok = $sth->execute(array (
        ':run_date' => date('Y-m-d h:i:s', strtotime($the_date)),
        ':run_time' => $eventFields->{"moving_time"},
        ':run_distance' => $eventFields->{"distance"}/1000,
        ':runner_id' => $runner_id,
        ':member_name' => $member_name,
        ':pulse' => $avg_pulse,
        ':total_elev' => $total_elev,
        ':strava_id' => $strava_id
    ));

    if (!$ok) {
        die(getErrorStatusWithDummyData("Failed to Create Event."));
    } else {
        $result = array (
            "strava_id" => $strava_id
        );
        echo returnJSONsuccess($result);
    }
    $conn = null;
} catch (PDOException $e) {
    die(getErrorStatusWithDummyData($e->getMessage()));
}
