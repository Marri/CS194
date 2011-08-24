<?php
$valid = true;

//Not a level that permits/needs upgrade
if($user->getLevel() != User::NORMAL_USER && $user->getLevel() != User::UPGRADE_USER) {
	$errors[] = 'You do not need an upgrade!';
	$valid = false;
}

//Can't afford
if($user->getAmount('squffy_dollar') < $cost) {
	$errors[] = 'You cannot afford an upgrade!';
	$valid = false;
}

if($valid) {
	if($user->getLevel() != User::UPGRADE_USER) { $user->setLevel(User::UPGRADE_USER); }
	$user->addSixMonths();
	$user->updateInventory('squffy_dollar', -1 * $cost, true);
}
?>