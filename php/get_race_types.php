<?php
require_once 'ajax_page_init.php';

try {
	$conn = getConnection();
	$sqlPhrase = "SELECT * FROM tl_race_type order by tl_race_type.id"; 
	
	echo getUsersAsJSON($conn,$sqlPhrase);
	$conn = null;
} catch (PDOException $e) {
	die(getErrorStatus("SQL: " .$sqlPhrase . " -- " . $e->getMessage()));
}	

// Fetch the users info from DB as JSON
function getUsersAsJSON($conn,$sqlPhrase) {
	$stmt = $conn->query($sqlPhrase);
	$result = $stmt->fetchAll(PDO :: FETCH_ASSOC);
	
	return returnJSONsuccess($result);
}