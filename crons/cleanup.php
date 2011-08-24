<?php
//Every few days
include('../includes/connect.php');

//Archive old data

//Optimize tables with lots of deletions
$query = "OPTIMIZE TABLE `vacations`, `degree_progress`, `pregnancies`";
runDBQuery($query);
?>