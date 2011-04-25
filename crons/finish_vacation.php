<?php
include('../includes/connect.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `vacations` WHERE TO_DAYS(now()) - TO_DAYS(date_return) >= 0';
$result = runDBQuery($query);

while($info = @mysql_fetch_assoc($result)) {
	$id = $info['user_id'];
	$level = $info['old_level'];
	$user = User::getUserByID($id);
	$user->setLevel($level);
}

$query = 'DELETE FROM `vacations` WHERE TO_DAYS(now()) - TO_DAYS(date_return) >= 0';
runDBQuery($query);
?>