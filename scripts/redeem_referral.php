<?php
$valid = true;

$prize = $_POST['prize'];
if(!Verify::VerifyID($prize)) {
	$valid = false;
	$errors[] = 'You chose a prize that does not exist.';
} else {
	$query = "SELECT * FROM referral_rewards WHERE prize_id = $prize";
	$result = runDBQuery($query);
	if(@mysql_num_rows($result) < 1) {
		$valid = false;
		$errors[] = 'You chose a prize that does not exist.';
	} else {
		$prize_info = @mysql_fetch_assoc($result);
		if($points < $prize_info['num_referred']) { 
			$valid = false;
			$errors[] = 'You do not have enough points to claim that reward.';
		}
	}
}

if($valid) {	
	$id = $prize_info['item_id'];
	$item = Item::getItemByID($id);
	
	$user->updateInventory($item->getColumnName(), $prize_info['quantity'], true);
	$newClaims = $claims + $prize_info['num_referred'];
	if($claims > 0) {
		$query = "UPDATE referral_reward_claims SET num_claimed = $newClaims WHERE user_id = $userid";
		runDBQuery($query);
	} else {
		$query = "INSERT INTO referral_reward_claims (user_id, num_claimed) VALUES ($userid, $newClaims)";
		runDBQuery($query);
	}
	$claims = $newClaims;
	$points = $num - $claims;
	
	$notice = 'Success! You have claimed your reward of ' . $prize_info['quantity'] . ' ';
	if($prize_info['quantity'] > 1) { $notice .= pluralize($item->getName()); }
	else { $notice .= $item->getName(); }
	$notices[] = $notice . '.';
	
}
?>