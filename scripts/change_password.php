<?php
$valid = true;

$old = $_POST['old_pass'];
$pass = $_POST['new_pass'];
$conf = $_POST['conf'];

$error = Verify::VerifyPasswords($pass, $conf);
if($error) {
	$errors = array_merge($errors, $error);
	$valid = false;
}

$error = Verify::VerifyPasswords($old);
if($error) {
	$errors = array_merge($errors, $error);
	$valid = false;
}

$secure = $user->securePassword($old);
$oldSecure = $user->getHash();

if($secure != $oldSecure) {
	$errors[] = 'You entered the wrong password';
	$valid = false;
}

if($valid) {
	$user->setPassword($pass);
	$notices[] = 'Success! You have changed your password.';
}
?>