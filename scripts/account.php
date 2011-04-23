<?php
$loggedin = false;
$user = NULL;
$userid = NULL;

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
	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$user = User::getUserByLogin($login, $pass);
	$user->fetchInventory();
}

//If currently logged in
if($user != NULL) {
	$user->seenNow();
	$userid = $user->getID();
	$loggedin = true;
}
?>