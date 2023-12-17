<?php

require_once 'ajax_page_init.php';
require_once 'user_common.php';

$user = getUser();

try {
	$conn = getConnection();
	$sql = 'UPDATE tl_runners SET member_name=:member_name,member_num=:member_num,email=:email WHERE id=:id';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
		':member_name' => $user->user_name,
		':member_num' => $user->member_num,
		':email' => $user->email,
		':id' => $user->user_id
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to update user."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}
