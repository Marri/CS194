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
$email = $_POST['email'];
$error = Verify::VerifyEmail($email);
if($error) {
	$errors[] = $error;
	$valid = false;
}

$pass = $_POST['password'];
$conf = $_POST['confirm'];
$error = Verify::VerifyPasswords($pass, $conf);
if($error) {
	$errors = array_merge($errors, $error);
	$valid = false;
}

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
		$safe = @mysql_real_escape_string($referer);
		$referBy = User::getUserByUsername($safe);
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
	$errors[] = 'You must agree to the Terms of Service and Privacy Policy.';
	$valid = false;
}

if($valid) {
	$user_name_db = strip_tags($user_name, Verify::USERNAME_TAGS);
	$user_name_db = mysql_real_escape_string($user_name);
	
	$newID = User::CreateUser($user_name, $pass, $login_name, $email);
	if(isset($referBy)) { 
		$query = 'INSERT INTO log_accepted_referrals (referer_id, referred_id, referred_username, date_joined)
				  VALUES (' . $referBy->getID() . ', ' . $newID . ", '" . $user_name . "', now())";
		runDBQuery($query);
	}
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$query = "INSERT INTO log_register (user_id, ip_address, date_joined) VALUES ($newID, '$ip', now())";
	runDBQuery($query);
	
	$notices[] = 'Success! You have created a Squffies account.<br />Please check your email for an activation key to activate your account.';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>