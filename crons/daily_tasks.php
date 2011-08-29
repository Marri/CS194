<?php
//Every day
include('../includes/connect.php');
include('../objects/user.php');

//Daily tasks for squffies
$query = "UPDATE `squffies` 
		  LEFT JOIN `vacations` ON vacations.`user_id` = squffies.`squffy_owner`
		  SET
			  `energy` = 100, 
			  `hunger` = CASE WHEN hunger > 95 THEN 100 ELSE hunger + 5 END,
			  `health` = CASE WHEN hunger > 75 THEN `health` - 10 WHEN hunger > 50 THEN `health` - 5 ELSE `health` END
		  WHERE `date_return` IS NULL;";
runDBQuery($query);
		  
//Daily tasks for users
$query = "UPDATE users 
		  SET 
			level_id = " . User::NORMAL_USER . ", 
			date_upgrade_ends = NULL 
		  WHERE 
			level_id = " . User::UPGRADE_USER . " AND
			TO_DAYS(date_upgrade_ends) - TO_DAYS(now()) <= 0";
runDBQuery($query);
?>