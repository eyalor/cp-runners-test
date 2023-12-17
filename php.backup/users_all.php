<?php
/*
 * Created on May 21, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'ajax_page_init.php';

try {
    $conn = getConnection();
    echo getUsersRecords($conn);
    $conn = null;
} catch (PDOException $e) {
    die(getErrorStatusWithDummyData($e->getMessage()));
}

function getUsersRecords($conn) {
    $sql = "SELECT tl_runners.id as user_id, tl_runners.member_name as member_name,tl_runners.member_num as member_num, tl_runners.email as email, tl_runners.birthday as birthdate, tl_runners.m_show_profile as active_runner FROM  tl_runners order by tl_runners.m_show_profile DESC,tl_runners.member_name";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
    // loop over the result and get the shoe distance  for each shoe
    return returnJSONsuccess($result);
}
?>