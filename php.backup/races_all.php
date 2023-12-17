<?php
/*
 * Created on May 21, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'ajax_page_init.php';

$runner_id = $memberAuthentication->getMemberId();
$validationResult = validatePositiveInt($runner_id);
if (!$validationResult->isValid()) {
    die(getErrorStatusWithDummyData("Invalid runner id: " . $validationResult->getMessage()));
}

try {
    $conn = getConnection();
    echo getRacesRecords($conn,$runner_id);
    $conn = null;
} catch (PDOException $e) {
    die(getErrorStatusWithDummyData($e->getMessage()));
}

function getRacesRecords($conn,$runner_id) {
    $sql = "SELECT tl_races.id as id, tl_races.race_date as race_date, tl_races.race_name as race_name, tl_race_type.type as race_type, tl_race_type.id as type_id, IFNULL(race_notes, '') as race_notes FROM  tl_races,tl_race_type where tl_races.type_id=tl_race_type.id order by tl_races.race_date DESC";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
    
    return returnJSONsuccess($result);
}


