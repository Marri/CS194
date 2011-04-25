<?php
include('../includes/connect.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `pregnancies` WHERE TO_DAYS(now()) - TO_DAYS(date_birth) >= 0';
$result = runDBQuery($query);

while($info = @mysql_fetch_assoc($result)) {
	$id = $info['mother_id'];
	$mother = Squffy::getSquffyByID($id);
	$id = $info['father_id'];
	$father = Squffy::getSquffyByID($id);
	$owner = $info['user_id'];
	Squffy::createChild($mother, $father, $owner);
}

$query = 'DELETE FROM `pregnancies` WHERE TO_DAYS(now()) - TO_DAYS(date_birth) >= 0';
runDBQuery($query);
?>