<?php
$valid = true;

$name = $_POST['friend'];
$email = $_POST['email'];
$note = $_POST['note'];

$error = Verify::VerifyEmail($email);
if($error) {
	$errors[] = $error;
	$valid = false;
}

if($valid) {
	$subj="Someone thinks you should check out Squffies.com!";
	$message =  $user->getUsername() . " told us you might like Squffies.  Squffies is a virtual pet game where you build your community from the ground up, choosing what your squffies look like, what they study, where they work, what they wear and more!\n\n";
	if($note != "") { $message .= "In their words:\n$note\n\n"; }
	$message .= "To create an account, please click the following link:\n\n";
	$message .= "http://www.squffies.com/register.php?refer=$userid\n\nThanks,\n-The Squffies team";
	$headers="From:support@squffies.com";
	mail($email, $subj, $message, $headers);
	
	$query = "INSERT INTO log_referrals (refer_id, email, date_sent) VALUES ($userid, '$email', now())";
	runDBQuery($query);
	$notices[] = "Success! Your invitation has been sent to $name.";
}
?>