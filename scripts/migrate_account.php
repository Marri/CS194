<?php
$valid = true;
include('./objects/badge.php');

$login = mysql_real_escape_string($_POST['old_login']);
$pass = $_POST['old_pass'];
$hash = sha1($pass);

$query = "SELECT * FROM log_migrated WHERE user_id = $userid";
$result = runDBQuery($query);
if(@mysql_num_rows($result) > 0) {
	$errors[] = 'You have already migrated an account.';
	$valid = false;
}

$query = "SELECT * FROM old_players WHERE hashword = '$hash' AND loginname = '$login'";
$result = runDBQuery($query);
if(@mysql_num_rows($result) < 1) {
	$errors[] = 'That account could not be found.';
	$valid = false;
}

if($valid) {
	$info = @mysql_fetch_assoc($result);
	$query = "SELECT * FROM log_migrated WHERE old_id = ". $info['userid'];
	$result = runDBQuery($query);
	if(@mysql_num_rows($result) > 0) {
		$errors[] = 'That account has already been migrated.';
		$valid = false;
	}
}

if($valid) {	
	//Upgrade if needed
	if($info['levelid'] == 3 || $info['levelid'] == 2) { 
		$user->setLevel(User::UPGRADE_USER);
		$user->addSixMonths();
	}
	
	//Give old badges
	foreach($info as $key => $val) {
		if(substr($key, 0, 5) != 'badge') { continue; }
		if($val > 0) {
			if($key == 'badge_ground') { Badge::GiveBadge($userid, Badge::GROUND_BADGE); }
			if($key == 'badge_tree') { Badge::GiveBadge($userid, Badge::TREE_BADGE); }
			if($key == 'badge_bday') { Badge::GiveBadge($userid, Badge::BDAY_BADGE); }
			if($key == 'badge_sand') { Badge::GiveBadge($userid, Badge::SAND_BADGE); }
			if($key == 'badge_lihtan') { Badge::GiveBadge($userid, Badge::LIHTAN_BADGE); }			
			if($key == 'badge_chatter') { Badge::GiveBadge($userid, Badge::CHATTER_BADGE); }
			if($key == 'badge_friend') { Badge::GiveBadge($userid, Badge::FRIEND_BADGE); }
			if($key == 'badge_bragger') { Badge::GiveBadge($userid, Badge::BRAG_BADGE); }
			if($key == 'badge_tper') { Badge::GiveBadge($userid, Badge::TP_BADGE); }
			if($key == 'badge_hero') { Badge::GiveBadge($userid, Badge::SHOVEL_BADGE); }
		}
	}
	
	//Give old items
	$query = "SELECT * FROM old_inventory WHERE user_id = " . $info['userid'];
	$result = runDBQuery($query);
	$inv = @mysql_fetch_assoc($result);
	foreach($inv as $key => $val) {
		if($key == 'user_id') { continue; }
		if($val < 1) { continue; }
		$user->updateInventory($key, $val, true);
	}
	
	//Give old squffies
	$user->migrateOldSquffies($info['userid']);
	
	//Log migration
	$ip = $_SERVER['REMOTE_ADDR'];
	$query = "INSERT INTO log_migrated VALUES (NULL, $userid, " . $info['userid'] . ", '$ip', now())";
	runDBQuery($query);
	
	$notices[] = 'Success! You have migrated your old account.';
}
?>