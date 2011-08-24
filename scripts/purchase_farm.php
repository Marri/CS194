<?php
$valid = true;

$name = $_POST['plot_name'];
if(!$name) {
	$valid = false;
	$errors[] = "You did not enter a name for your new farm plot.";
}

$type = $_POST['type'];
if($type != Farm::FARM && $type != Farm::ORCHARD && $type != Farm::GARDEN) {
	$valid = false;
	$errors[] = "You did not pick a valid type for your new farm plot.";	
} elseif($type == Farm::FARM) {
	$cost = 1;
} elseif($type == Farm::ORCHARD) {
	$cost = 2;
} elseif($type == Farm::GARDEN) {
	$cost = 3;
}

if($user->getAmount('chestnut') < $cost) {
	$valid = false;
	$errors[] = 'You cannot afford this plot.';
}

if($valid) {
	Farm::CreateFarm($name, $type, $userid);
	$user->updateInventory('chestnut', -1 * $cost, true);
}
?>