<?php
//Every hour
include('../includes/connect.php');
include('../objects/squffy.php');

//Set farms to harvest
$query = "UPDATE `farms` SET date_ripe = NULL, cur_state = 'Grown', dryness = NULL, weeds=NULL WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_ripe) >= 0";
runDBQuery($query);

//Queue current jobs
$query = 'SELECT * FROM `jobs_farming` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_finished) >= 0';
$result = runDBQuery($query);

//Delete finished jobs
$query = 'DELETE FROM `jobs_farming` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_finished) >= 0';
runDBQuery($query);

//Crawl jobs
$farms_done = array();
$changed = array();
$workers = '';
while($info = @mysql_fetch_assoc($result)) {
	$workers .= ', ' . $info['farmer_id'];
	
	$farm = $info['farm_id'];
	$farms_done[$farm] = $info['chore_type'];
}

//Crawl farm chores finished
$farms_plowed = '';
$farms_fertilized = '';
$farms_planted = '';
$farms_watered = '';
$farms_weeded = '';
foreach($farms_done as $farm => $chore) {
	if($chore == 'Plow') {
		$farms_plowed .= ', ' . $farm;
	} elseif($chore == 'Fertilize') {
		$farms_fertilized .= ', ' . $farm;
	} elseif($chore == 'Plant') {
		$farms_planted .= ', ' . $farm;
	} elseif($chore == 'Water') {
		$farms_watered .= ', ' . $farm;
	} elseif($chore == 'Weed') {
		$farms_weeded .= ', ' . $farm;
	}
}

if(strlen($farms_weeded) > 0) {
	$query = "UPDATE farms SET weeds = weeds - 15, num_workers = 0 WHERE farm_id IN (" . substr($farms_weeded, 2) . ")";
	runDBQuery($query);
}

if(strlen($farms_watered) > 0) {
	$query = "UPDATE farms SET dryness = dryness - 15, num_workers = 0 WHERE farm_id IN (" . substr($farms_watered, 2) . ")";
	runDBQuery($query);
	
	$query = "SELECT user_id FROM farms WHERE farm_id IN (" . substr($farms_watered, 2) . ") GROUP BY user_id";
	$result = runDBQuery($query);
	$users = '';
	while($info = @mysql_fetch_assoc($result)) {
		$users .= ', ' . $info['user_id'];
		$changed[] = $info['user_id'];
	}
	$query = "UPDATE inventory SET water_pail = water_pail + 1 WHERE user_id IN (" . substr($users, 2) . ")";
	runDBQuery($query);
}

if(strlen($farms_fertilized) > 0) {
	$query = "UPDATE farms SET is_fertilized = 'true', num_workers = 0 WHERE farm_id IN (" . substr($farms_fertilized, 2) . ")";
	runDBQuery($query);
}

if(strlen($farms_planted) > 0) {
	$date = time() + 60 * 60 * 24 * 5;
	$date = date("Y-m-d H:i:s", $date);
	$query = "UPDATE farms SET cur_state = 'Planted', dryness = 0, weeds = 0, num_workers = 0, date_ripe = '$date' WHERE farm_id IN (" . substr($farms_planted, 2) . ")";
	runDBQuery($query);
}

//Give back hoes
if(strlen($farms_plowed) > 0) {
	$query = "UPDATE farms SET cur_state = 'Plowed', num_workers = 0 WHERE farm_id IN (" . substr($farms_plowed, 2) . ")";
	runDBQuery($query);
	
	$query = "SELECT user_id FROM farms WHERE farm_id IN (" . substr($farms_plowed, 2) . ") GROUP BY user_id";
	$result = runDBQuery($query);
	$users = '';
	while($info = @mysql_fetch_assoc($result)) {
		$users .= ', ' . $info['user_id'];
		$changed[] = $info['user_id'];
	}
	$query = "UPDATE inventory SET hoe = hoe + 1 WHERE user_id IN (" . substr($users, 2) . ")";
	runDBQuery($query);
}

//Set workers to not be working
if(strlen($workers) > 0) {
	$workers = substr($workers, 2);
	$query = "UPDATE `squffies` SET `is_working` = 'false' WHERE squffy_id IN ($workers)";
	runDBQuery($query);
}

//Set cached changed for all changed users
if(sizeof($changed) > 0) {
	$changed = array_unique($changed);
	foreach($changed as $change) {
		$query = "INSERT INTO cache_changed VALUES ($change)";
		runDBQuery($query);
	}
}

//Increase weeds and water
$query = "UPDATE farms SET 
`dryness` = CASE WHEN dryness > 95 THEN 100 ELSE dryness + 5 END,
`weeds` = CASE WHEN weeds > 95 THEN 100 ELSE weeds + 5 END
WHERE dryness IS NOT NULL";
runDBQuery($query);

//Kill
$query = "UPDATE farms SET cur_state = 'Dead' WHERE dryness = 100 OR weeds = 100";
runDBQuery($query);
?>