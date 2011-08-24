<?php
$valid = true;

if(isset($_GET['key'])) {
	$key = $_GET['key'];
} elseif(isset($_POST['key'])) {
	$key = $_POST['key'];
} else {
	$errors[] = 'You did not enter an activation key.';
	$valid = false;
}

$error = Verify::VerifyActivation($key);
if($error) {
	$errors[] = $error;
	$valid = false;
} else {
	$user = User::GetUserByActivation($key);
	if($user == NULL) {
		$errors[] = 'That activation key does not exist.';
		$valid = false;
	} else {
		if($user->getLevel() != User::PRE_ACTIVATED_USER) {
			$errors[] = 'That user has already been activated.';
			$valid = false;
		}
	}
}

if($valid) {
	$user->setLevel(User::NORMAL_USER);
	$query = "DELETE FROM user_activation WHERE activate = '$key'";
	runDBQuery($query);
	
	$notices[] = 'Success! You have activated your Squffies account and can now log in. Welcome!';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>