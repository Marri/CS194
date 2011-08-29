<?php
$loggedin = false;
$activated = false;
$user = NULL;
$userid = NULL;
$global_error = "";

session_save_path("/home/users/web/b395/nf.spstrade/cgi-bin/tmp");
session_start();

//Log out
if(isset($_POST['logging_out'])) {
	$_SESSION['user'] = NULL;
	unset($_SESSION['user']);
}

//Logged in
if(isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	$user->checkCacheUpdate();
} 

//Logging in
else if(isset($_POST['logging_in'])) {
	$success = true;
	$login = $_POST['login_name'];
	$pass = $_POST['password'];
	
	$error = Verify::VerifyLogin($login, true);
	if($error) {
		$success = false;
		$errors[] = $error;
	} else {
		$user = User::getUserByLogin($login, $pass);	
		if($user == NULL) {
			$success = false;
			$errors[] = 'That login/password combination could not be found.';
		} elseif($user->getLevel() == User::PRE_ACTIVATED_USER) {
			$errors[] = 'Please <a href="activate.php">activate your account</a> before logging in.<br />
						&nbsp;&nbsp;&nbsp;If you cannot find the email we sent, <a href="resend.php">we can send it again</a>.<br />
						&nbsp;&nbsp;&nbsp;If you think you registered with the wrong email address, <a href="fix_email.php">you can update it</a>.';
			$success = false;
		} elseif($user->getLevel() == User::FROZEN_USER) {
			$errors[] = 'Your account has been frozen.  If you have questions, please send them to support@squffies.com.';
			$success = false;
		} elseif($user->getLevel() == User::VACATION_USER) {
			$success = false;
			$errors[] = 'Your account is set to vacation mode.  You cannot log in until vacation mode expires, but never fear- time has been stopped until you return.';
		} else {
			$user->fetchInventory();
		}
	}
	
	if(!$success) {
		$user = NULL;
	}
}

//If currently logged in
if($user != NULL) {
	$user->seenNow();
	$userid = $user->getID();
	$loggedin = true;
	$_SESSION['user'] = $user;
}
?>