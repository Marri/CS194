<?php
$valid = true;

$login = $_POST['login'];
$confirm = $_POST['confirm'];

if($login != $confirm) {
	$errors[] = 'Your login names did not match.';
	$valid = false;
}

$error = Verify::VerifyLogin($login, true);
if($error) {
	$errors[] = $error;
	$valid = false;
} else {
	$user = User::getUserByLogin($login, NULL, true);
	if($user == NULL) {
		$errors[] = 'There is no user with that login name.';
		$valid = false;
	} elseif($user->getLevel() == User::FROZEN_USER) {
		$errors[] = 'That user has been frozen and cannot reset their password.';
		$valid = false;
	} elseif($user->getLevel() == User::VACATION_USER) {
		$errors[] = 'That user is on vacation and cannot reset their password.';
		$valid = false;
	} elseif($user->getLevel() == User::PRE_ACTIVATED_USER) {
		$errors[] = 'That user has not activated their account yet and cannot reset their password.';
		$valid = false;
	}
}

if($valid) {
	$user->resetPassword();
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$id = $user->getID();
	$level = $user->getLevel();
	$query = "INSERT INTO log_password_reset (user_id, old_level, ip_address, date_reset) VALUES ($id, $level, '$ip', now())";
	runDBQuery($query);
	
	$notices[] = 'Success! You have activated your Squffies account and can now log in. Welcome!';
}
?>