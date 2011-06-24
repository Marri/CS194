<?php
date_default_timezone_set("America/Los_Angeles");
$errors = array();
$notices = array();

$objects = 
array(
	'appearance', 
	'cost',
	'design', 
	'item', //Before food 
	'food',
	'forums',
	'lot', 
	'messaging', 
	'notification', 
	'personality', 
	'recipe',
	'squffy',
	'user',
	'verify'
);
foreach($objects as $object) {
	include('./objects/' . $object . '.php');
}

include('./includes/utils.php');
include('./includes/gzip.php'); //Compresss files
include('./includes/connect.php'); //Connect to the database
include('./scripts/account.php'); //See if a user is logged in
include('./includes/layout.php'); //Display layout

?>