<?php

require_once 'ajax_page_init.php';

$raceId = $_GET['race_id'];

try {
    $conn = getConnection();
    $sql = "SELECT tl_runners.member_name AS runner_name, tl_races_data.runner_id AS runner_id, tl_races.race_name AS race_name, tl_races.race_date AS race_date, tl_races_data.run_time AS run_time, tl_races_data.notes AS notes FROM tl_races_data,tl_races,tl_runners WHERE tl_races_data.race_id= '" . $raceId . "' and tl_races_data.race_id=tl_races.id and tl_runners.id = tl_races_data.runner_id order by tl_races_data.run_time";
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
	echo returnJSONsuccess($result);
    $conn = null;
} catch (PDOException $e) {
    die(getErrorStatusWithDummyData($e->getMessage()));
}
?>

