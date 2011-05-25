<?php
include('../includes/connect.php');
include('../objects/personality.php');
include('../objects/appearance.php');
include('../objects/cost.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `degree_progress` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
$result = runDBQuery($query);

$teachers = "";
while($info = @mysql_fetch_assoc($result)) {
	$id = $info['squffy_id'];
	$squffy = Squffy::getSquffyByID($id);
	$squffy->finishDegree();
	
	//If a teacher, add to list to finish working
	$id = $info['teacher_id'];
	if($id > 0) {
		$squffy = Squffy::getSquffyByID($id);
		$teachers .= ', ' . $squffy->getID();
	}
}

$query = 'DELETE FROM `degree_progress` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
runDBQuery($query);

if(strlen($teachers) > 0) {
	$teachers = substr($teachers, 2);
	$query = "UPDATE `squffies` SET `is_working` = 'false' WHERE squffy_id IN ($teachers)";
	runDBQuery($query);
}
?>