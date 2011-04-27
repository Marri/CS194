<?php
include('../includes/connect.php');

//Daily tasks for squffies
$query = "UPDATE `squffies` 
		  SET
			  `energy` = 100, 
			  `hunger` = CASE WHEN hunger > 95 THEN 100 ELSE hunger + 5 END,
			  `health` = CASE WHEN hunger > 75 THEN `health` - 10 WHEN hunger > 50 THEN `health` - 5 ELSE `health` END
		  WHERE 1;";
runDBQuery($query);
		  
//Daily tasks for users?
		  
//De-upgrade users
?>