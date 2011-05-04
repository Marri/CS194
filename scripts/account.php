<?php
$loggedin = false;
$activated = false;
$user = NULL;
$userid = NULL;
$global_error = "";
session_start();

//Log out
if(isset($_POST['logging_out'])) {
	unset($_SESSION['user']);
}

//Logged in
if(isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	$user->checkCacheUpdate();
} 

//Logging in
else if(isset($_POST['logging_in'])) {
	$login = $_POST['login_name'];
	$pass = $_POST['password'];
	$user = User::getUserByLogin($login, $pass);
	if($user != NULL && $user->isActivated() == 'false') { 
		$global_error = $global_error." Please Activate Your Account ";
		$user = NULL; }
	else { $activated = true; }
	if($user != NULL) { $user->fetchInventory(); }
}

//If currently logged in
if($user != NULL) {
	$user->seenNow();
	$userid = $user->getID();
	$loggedin = true;
	$_SESSION['user'] = $user;
}
?>