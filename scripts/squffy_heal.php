<?php
$valid = true;

$id = getID('squffy_id');
$squffy = Squffy::getSquffyByID($id);
if($squffy == NULL) {
	$errors[] = "You must pick a squffy to heal.";
	$valid = false;
} else {
	$original = $squffy->getHealth();
	if($original > 99) {
		$errors[] = $squffy->getName() . " does not need healing right now.";
		$valid = false;		
	}
}

$inventory = $user->getInventory();
if($inventory['pistachio'] < 1) {
	$errors[] = "You cannot afford this healing.";
	$valid = false;
}

if($valid) {
	$user->updateInventory('pistachio', -1, true);
	$squffy->heal();
	$change = $squffy->getHealth() - $original;
	$notices[] = possessive($squffy->getName()) . " health has increased by $change.";
}
?>