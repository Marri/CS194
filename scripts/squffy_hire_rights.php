<?php
$valid = true;
if(!isset($squffy)) { die(); }

$cost = $_POST['h_cost'];
$price = $squffy->getHirePrice();
$inventory = $user->getInventory();
$col = '';
$change = 0;

if($cost == 'item') {
	$col = $price->getColumnName();
	$change = $price->getItemPrice();
	if($inventory[$col] < $price->getItemPrice()) {
		$errors[] = "You cannot afford to hire this squffy.";
		$valid = false;
	}
} elseif($cost == 'sd') {
	$col = 'squffy_dollar';
	$change = $price->getSDPrice();
	if($inventory['squffy_dollar'] < $price->getSDPrice()) {
		$errors[] = "You cannot afford to hire this squffy.";
		$valid = false;
	}
} else {
	$errors[] = 'You must pick a valid way to pay for this squffy\'s time.';
	$valid = false;
}

if($squffy->getOwnerID() == $userid) {
	$errors[] = 'This is your squffy.';
	$valid = false;
} elseif($squffy->canWorkFor($userid)) {
	$errors[] = 'You have already hired this squffy.';
	$valid = false;
}

if($valid) {
	$squffy->setHireRights($user);
	$user->updateInventory($col, -1 * $change, true);
	$owner = User::getUserByID($squffy->getOwnerID());
	$owner->updateInventory($col, $change, true);
	User::cacheChanged($owner->getID());
	
	$notices[] = 'You have purchased the right to hire ' . $squffy->getName() . '.';
}	
?>