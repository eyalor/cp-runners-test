<?php
require_once 'ajax_page_init.php';

$conn = getConnection();

$race_id = $_GET['race_id'];

  
//$validationResult = validatePositiveInt($runner_id);
//if (!$validationResult->isValid()) {
//    die(getErrorStatusWithDummyData("Invalid runner id: " . $validationResult->getMessage()));
//}

try {
    $sql = "SELECT tl_runners.member_name as member_name, tl_runners.id as id  FROM tl_runners WHERE tl_runners.id NOT IN (SELECT tl_races_data.runner_id FROM tl_races_data WHERE tl_races_data.race_id  = '" . $race_id . "') and tl_runners.m_show_profile = '1' ORDER BY tl_runners.member_name";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
}
catch (PDOException $e) {
    die(getErrorStatusWithDummyData($e->getMessage()));
}

//$data [] = Array ("Name", $result[0]['member_name']);

$conn = null;
//if (empty($data)) {
//    $data = '';
//}
echo returnJSONsuccess($result);