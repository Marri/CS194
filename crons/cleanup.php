<?php
include('../includes/connect.php');

//Archive old data
$query = "DELETE FROM `newbie_packs` WHERE `items_claimed` = 'true' AND `squffy_made` = 'true';";
runDBQuery($query);

//Optimize tables with lots of deletions
$query = "OPTIMIZE TABLE `newbie_packs`, `vacations`, `degree_progress`, `pregnancies`";
runDBQuery($query);
?>