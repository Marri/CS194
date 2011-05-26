<?php
date_default_timezone_set("America/Los_Angeles");
$errors = array();
$notices = array();

$objects = array('user', 'appearance', 'cost', 'design', 'forums', 'item', 'lot', 'messaging', 'notification', 'personality', 'squffy', 'food', 'recipe');
foreach($objects as $object) {
	include('./objects/' . $object . '.php');
}

include('./includes/utils.php');
include('./includes/gzip.php'); //Compresss files
include('./includes/connect.php'); //Connect to the database
include('./scripts/account.php'); //See if a user is logged in
include('./includes/layout.php'); //Display layout

?>