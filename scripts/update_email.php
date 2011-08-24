<?php
$valid = true;

$login = $_POST['login'];
$pass = $_POST['pass'];
$email = $_POST['email'];

$error = Verify::VerifyLogin($login, true);
if($error) {
	$errors[] = $error;
	$valid = false;
} else {
	$user = User::getUserByLogin($login, $pass);	
	if($user == NULL) {
		$errors[] = 'That login/password combination could not be found.';
		$valid = false;
	} /*elseif($user->getLevel() != User::PRE_ACTIVATED_USER) {
		$errors[] = 'That account is already activated. Please use the Edit Account page to update your email address.';
		$valid = false;
	}*/
}

$error = Verify::VerifyEmail($email);
if($error) {
	$errors[] = $error;
	$valid = false;
}	

if($valid) {
	$query = "UPDATE users SET `email_address` = '$email' WHERE user_id = " . $user->getID();
	runDBQuery($query);
	
	$notices[] = 'Success! You have updated your email address.  Do you maybe want to <a href="resend.php">resend your activation key</a>?';
}
?>