<?php
$valid = true;

$pass = $_POST['pass'];
$conf = $_POST['confirm'];
$error = Verify::VerifyPasswords($pass, $conf);
if($error) {
	$errors = array_merge($errors, $error);
	$valid = false;
}

if($valid) {
	$query = "SELECT * FROM log_password_reset WHERE user_id = $userid AND old_level != " . User::RESET_PASSWORD_USER . " ORDER BY date_reset DESC LIMIT 1";
	$result = runDBQuery($query);
	$info = @mysql_fetch_assoc($result);
	$old = $info['old_level'];
	$user->setLevel($old);
	$user->setPassword($pass);
}
?>