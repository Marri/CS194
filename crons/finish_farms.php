<?php
include('../includes/connect.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `jobs_farming` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
$result = runDBQuery($query);

while($info = @mysql_fetch_assoc($result)) {
}

$query = 'DELETE FROM `jobs_farming` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
runDBQuery($query);
?>