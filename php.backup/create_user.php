<?php
	require_once 'ajax_page_init.php';
	require_once 'user_common.php';
	$user = getUser();
try {
	$conn = getConnection();
	$sql = 'INSERT INTO tl_runners (member_name,email,member_num,m_show_profile) VALUES (:user_name,:email,:password,:active_runner)';
	$sth = $conn->prepare($sql);
	$ok = $sth->execute(array (
	':user_name'=> $user->user_name,
	':email' => $user->email,
	':password' => $user->member_num,
	':active_runner' => $user->active_runner
	));
	if (!$ok) {
		die(getErrorStatusWithDummyData("Failed to Create a user."));
	} else {
		echo returnJSONsuccess("");
	}
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatusWithDummyData($e->getMessage()));
}
?>