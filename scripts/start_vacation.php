<?php
$valid = true;

$days = $_POST['num_days'];
if(!is_numeric($days)) {
	$valid = false;
	$errors[] = 'You must enter a number of days for your vacation.';
} elseif ($days < 3) {
	$valid = false;
	$errors[] = 'You must go on vacation for at least three days.';
}
/*
CREATE TABLE IF NOT EXISTS vacations (
	user_id mediumint unsigned,
	date_return datetime,
	old_level tinyint unsigned
);*/
if($valid) {
	$date = date("Y-m-d H:i:s");
	$date = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +" . $days . " days");
	$date = date("Y-m-d H:i:s", $date);
	$level = $user->getLevel();
	$query = "INSERT INTO vacations VALUES ($userid, '$date', $level)";
	runDBQuery($query);
	
	$user->addDaysToUpgrade($days);
	$user->setLevel(User::VACATION_USER);
	$query = "UPDATE pregnancies SET date_birth = adddate(date_birth, $days) WHERE user_id = $userid";
	runDBQuery($query);
	unset($_SESSION['user']);
	$notes[] = 'Success! You have just started a ' . $days . ' day vacation. You have been logged out. Have fun!';
	//TODO handle farms
}
?>