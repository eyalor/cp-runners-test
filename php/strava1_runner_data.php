<?php
header('Access-Control-Allow-Origin: *');




//error_reporting(E_ALL ^ E_NOTICE);
require_once 'member_authentication.php';
require_once 'constants.php';
require_once 'utils.php';
$memberAuthentication = new memberAuthentication();

    if (!$memberAuthentication->isMemberAuthenticated())
    {
        //$memberAuthentication->redirectToLoginPage();
        echo "11111111111111";
    }

$memberId = $memberAuthentication->getMemberId();
echo $memberId;
// $json = file_get_contents('php://input');
// $data = json_decode($json);
// $memberAuthentication = new memberAuthentication();
// if (!$memberAuthentication->isMemberAuthenticated())
// {
//     //echo $data->email;
//     //echo $data->password;
//     $memberAuthentication->login($data->email,$data->password,true);
//     if (!$memberAuthentication->isMemberAuthenticated()){
//         return;
//     }
// }
//$runner_id = $memberId;
//$runner_id = $memberAuthentication->getMemberId();
//echo "--->  $runner_id";
// try {
//     $conn = getConnection();
//     $sql = "SELECT * from tl_runners where tl_runners.id = '" . $memberId . "';" ; 
//     $stmt = $conn->query($sql);
//     $result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
//     echo returnJSONsuccess($result);
//     $conn = null;
// } catch (PDOException $e) {
//     die(getErrorStatusWithDummyData($e->getMessage()));
// }
