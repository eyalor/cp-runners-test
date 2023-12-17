<?php
require_once 'ajax_page_init.php';

$conn = getConnection();

$member_id = $_GET['runner_id'];
//$validationResult = validatePositiveInt($runner_id);
//if (!$validationResult->isValid()) {
//    die(getErrorStatusWithDummyData("Invalid runner id: " . $validationResult->getMessage()));
//}

//try {
//    $sql = "SELECT tl_runners.member_name FROM tl_runners WHERE tl_runners.id = '" . $runner_id . "'";
//    $stmt = $conn->query($sql);
//    $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
//}
//catch (PDOException $e) {
//    die(getErrorStatusWithDummyData($e->getMessage()));
//}


//$data [] = Array ("Name", $result[0]['member_name'], "Weekly sessions");

$sql =

"SELECT tl_races_data.runner_id AS runner_id, tl_races_data.race_id AS race_id, tl_races.race_date AS race_date, tl_races.race_name AS race_name, tl_race_type.type AS race_type, tl_race_type.race_distance AS race_distance, tl_races_data.run_time AS result,  tl_races_data.run_time/tl_race_type.race_distance as pacet, tl_races_data.notes as notes
	FROM tl_races_data,tl_races,tl_race_type,tl_runners
    WHERE ( tl_races_data.runner_id = tl_runners.id 
        AND tl_races_data.race_id = tl_races.id
        AND tl_race_type.id = tl_races.type_id)
		AND tl_runners.id = '" . $member_id . "'
    ORDER BY race_date DESC";

$sth = $conn->prepare($sql, array(
    PDO :: ATTR_CURSOR => PDO :: CURSOR_FWDONLY
));

$ok = $sth->execute();
if (!$ok) {
    die(getErrorStatusWithDummyData("Failed to execute prepared statment"));
} else {
    foreach ($sth->fetchAll(PDO :: FETCH_ASSOC) as $row) {

		 $data[] = array($row['race_id'], $row['runner_id'], date('Y', strtotime($row['race_date'])), $row['race_name'], $row['race_type'], substr(date("H:i:s", $row['result']),1,7), substr(date("i:s", $row['pacet']),1,4), $row['notes'] );    
	}
}

$conn = null;
if (empty($data)) {
    $data = '';
}
echo returnJSONsuccess($data);