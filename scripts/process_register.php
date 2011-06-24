<?php
$valid = true;

$user_name = $_POST['username'];
$error = Verify::VerifyUsername($user_name);
if($error) {
	$errors[] = $error;
	$valid = false;
}

$login_name = $_POST['login'];
$error = Verify::VerifyLogin($login_name);
if($error) {
	$errors[] = $error;
	$valid = false;
}

//email address
//password

//referral
$referer = $_POST['referer'];
if(strlen($referer) > 0) {
	if(is_numeric($referer)) {
		$referBy = User::getUserByID($referer);
		if($referBy == NULL) {
			$errors[] = 'The user ID you entered for the person who referred you was invalid.';
			$valid = false;
		}
	} else if(!Verify::VerifyUsername($referer, true)) {
		$referBy = User::getUserByUsername($referer);
		if($referBy == NULL) {
			$errors[] = 'The username you entered for the person who referred you was invalid.';
			$valid = false;
		}
	} else {
		$errors[] = 'You did not enter either a valid username or user ID for the person who referred you.';
		$valid = false;
	}
}

if(!isset($_POST['agree'])) {
	$errors[] = 'You must agree to both the Terms of Service and Privacy Policy before you can register.';
	$valid = false;
}

if($valid) {
	$user_name_db = strip_tags($username, Verify::USERNAME_TAGS);
	$user_name_db = mysql_real_escape_string($user_name);
	
	if(!isset($referBy)) { 
		echo 'register!'; 
		$notices[] = 'Success! You have created a Squffies account.<br />Please check your email for an activation key to activate your account.';
	} else { 
		echo 'referred register!'; 
		$notices[] = 'Success! You have created a Squffies account.<br />Please check your email for an activation key to activate your account.';
	}
}
?>