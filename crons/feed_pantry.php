<?php
//Every six hours
include('../includes/connect.php');
include('../objects/cost.php');
include('../objects/squffy.php');
include('../objects/personality.php');
include('../objects/item.php');
include('../objects/food.php');
include('../includes/utils.php');

$query = "SELECT * FROM squffies WHERE hunger > 0 ORDER BY squffy_owner, hunger DESC";
$squffies = Squffy::getSquffies($query);
$pantries = array();
$skip = array();
while(true) {
	if(sizeof($squffies) < 1) { break; } //No squffies left to feed
	
	//Pop first squffy from array
	$squffy = array_shift($squffies);
	$owner = $squffy->getOwnerID();	
	
	//Owner has nothing in their pantry
	if(isset($skip[$owner])) { continue; }
	
	//Find their owner's pantry
	$pantry = NULL;
	if(!isset($pantries[$owner])) {
		$query = "SELECT * FROM pantry WHERE user_id = $owner";
		$result = runDBQuery($query);
		$pantry = @mysql_fetch_assoc($result);
	} else {
		$pantry = $pantries[$owner];
	}
	
	//Feed one thing from the pantry
	$wasFed = false;
	foreach($pantry as $food => $amount) {
		if($food == 'user_id') { continue; }
		if($amount < 1) { continue; }
		$wasFed = true;
		$pantry[$food]--;
		$query = "UPDATE pantry SET $food = $food - 1 WHERE user_id = $owner";
		runDBQuery($query);
		$item = Item::getItemFromName(myucfirst(str_replace("_", " ", $food)));
		$squffy->feed($item);
		if($squffy->getHunger() > 0) { $squffies[] = $squffy; }
		break;
	}
	if(!$wasFed) { $skip[$owner] = true; }
	
	$pantries[$owner] = $pantry;
}
/*
foreach($squffies as $squffy) {
	$owner = $squffy->getOwnerID();	
	$query = "SELECT * FROM pantry WHERE user_id = $owner";
	$result = runDBQuery($query);
	$info = @mysql_fetch_assoc($result);
	echo $squffy->getName();
	foreach($info as $key=>$val) {
		if($key == 'user_id') { continue; }
		if($val < 1) { continue; }
		echo 'Can be fed '. $key;
	}
	echo '<br>';
}*/

?>