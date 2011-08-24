<?php
//Once per day
include('../includes/connect.php');
include('../objects/user.php');

$query = 'SELECT * FROM `vacations` WHERE TO_DAYS(now()) - TO_DAYS(date_return) >= 0';
$result = runDBQuery($query);

$query = 'DELETE FROM `vacations` WHERE TO_DAYS(now()) - TO_DAYS(date_return) >= 0';
runDBQuery($query);

while($info = @mysql_fetch_assoc($result)) {
	$id = $info['user_id'];
	$level = $info['old_level'];
	$user = User::getUserByID($id);
	$user->setLevel($level);
}
?>