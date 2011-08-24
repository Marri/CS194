<?php
include('../includes/connect.php');
include('../objects/verify.php');
include('../objects/user.php');

$entry = $_GET['data'];
$error = Verify::VerifyUsername($entry, true);
$users = array();
if(!$error) {
	$entry = mysql_real_escape_string($entry);
	$query = "SELECT squffy_id, squffy_name FROM squffies WHERE squffy_name LIKE '%$entry%'";
	$result = runDBQuery($query);
	while($user = @mysql_fetch_assoc($result)) {
		$users[] = array('id' => $user['squffy_id'], 'name' => $user['squffy_name']);
		if(sizeof($users) >= 10) { 
			$users[] = array('extended' => true);
			break; 
		}
	}
}

echo json_encode($users);
?>