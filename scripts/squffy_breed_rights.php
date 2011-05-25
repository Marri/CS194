<?php
$valid = true;
if(!isset($squffy)) { die(); }

$cost = $_POST['b_cost'];
$price = $squffy->getBreedPrice();
$inventory = $user->getInventory();
$col = '';
$change = 0;

if($cost == 'item') {
	$col = $price->getColumnName();
	$change = $price->getItemPrice();
	if($inventory[$col] < $price->getItemPrice()) {
		$errors[] = "You cannot afford to breed to this squffy.";
		$valid = false;
	}
} elseif($cost == 'sd') {
	$col = 'squffy_dollar';
	$change = $price->getSDPrice();
	if($inventory['squffy_dollar'] < $price->getSDPrice()) {
		$errors[] = "You cannot afford to breed to this squffy.";
		$valid = false;
	}
} else {
	$errors[] = 'You must pick a valid way to pay for this breeding.';
	$valid = false;
}

if($squffy->getOwnerID() == $userid) {
	$errors[] = 'This is your squffy.';
	$valid = false;
} elseif($squffy->canBreedFor($userid)) {
	$errors[] = 'You have already bought breeding rights for this squffy.';
	$valid = false;
}

if($valid) {
	$squffy->setBreedRights($user);
	$user->updateInventory($col, -1 * $change, true);
	$owner = User::getUserByID($squffy->getOwnerID());
	$owner->updateInventory($col, $change, true);
	User::cacheChanged($owner->getID());
	
	$notices[] = 'You have purchased the right to breed to ' . $squffy->getName() . '.';
}	
?>