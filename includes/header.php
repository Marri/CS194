<?php
date_default_timezone_set("America/Los_Angeles");
$errors = array();
$notices = array();

include('./includes/utils.php');
include('./includes/gzip.php'); //Compresss files
include('./includes/connect.php'); //Connect to the database
include('./objects/user.php'); //Class defining the object representing the current user
include('./scripts/account.php'); //See if a user is logged in
include('./includes/layout.php'); //Display layout
?>