<?php
include("./includes/header.php");
	
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	
	$query = "SELECT login FROM `users` WHERE `username` = '".$username."'";
	$results = runDBQuery($query);
	$user;
	while($users = mysql_fetch_assoc($results)) {
		$user = getUserByLogin($results['login'], $password);
	}
	if($user == NULL){ echo "not valid login";}
	else { 
		echo "logged in";
		session_start();
	}
	
	
?>