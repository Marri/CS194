<?php
include('../includes/connect.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `degree_progress` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
$result = runDBQuery($query);

while($info = @mysql_fetch_assoc($result)) {
	$id = $info['squffy_id'];
	$squffy = Squffy::getSquffyByID($id);
	$squffy->finishDegree();
}

$query = 'DELETE FROM `degree_progress` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
runDBQuery($query);
?>