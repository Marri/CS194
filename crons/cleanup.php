<?php
include('../includes/connect.php');

//Archive old data
//TODO messages

//Optimize tables with lots of deletions
$query = "OPTIMIZE TABLE `vacations`, `degree_progress`, `pregnancies`";
runDBQuery($query);
?>