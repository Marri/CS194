<?php
$valid = true;

$email = $_POST['email'];
$error = Verify::VerifyEmail($email, true);
if($error) { 
	$errors[] = $error; 
	$valid = false;
} else {
	$activating = User::GetUserByEmail($email);
	if($activating == NULL) {
		$valid = false;
		$errors[] = 'There is no account with that email.';
	} elseif ($activating->getLevel() != User::PRE_ACTIVATED_USER) {
		$valid = false;
		$errors[] = 'That account has already been activated.';
	} else {
		$id = $activating->getID();
		$username = $activating->getUsername();
		$query = "SELECT * FROM user_activation WHERE user_id = $id";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) != 1) {
			$valid = false;
			$errors[] = 'There is no activation key associated with that email.';
		} else {
			$info = @mysql_fetch_assoc($result);
			$key = $info['activate'];
		}
	}
}

if($valid) {	
	User::SendActivationEmail($email, $username, $key);
	$notices[] = 'Success! Your activation key has been emailed to you.';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>