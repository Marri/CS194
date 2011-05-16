<?php
$valid = true;
$changes = "";
if(!isset($squffy)) { die(); }

$newName = $_POST['squffy_name'];
$newHire = $_POST['hireable'] == 'y' ? true : false;
$newBreed = $_POST['breedable'] == 'y' ? true : false;
$newHSD = $_POST['hire_sd'];
$newHI = $_POST['hire_item'];
$newHIA = $_POST['hire_amount'];
$newBSD = $_POST['breed_sd'];
$newBI = $_POST['breed_item'];
$newBIA = $_POST['breed_amount'];

if(!$newName) {
	$errors[] = "You must choose a name for your squffy.";
	$valid = false;
} else if(strip_tags($newName)!= $newName || strlen($newName) > 100) {
	$errors[] = "You did not enter a valid name for your squffy.";
	$valid = false;
} else if($newName != $name) {
	$changes .= ", squffy_name = '$newName'";
	$name = $newName;
}

if($newHire != $hireable) {
	$changes .= ", is_hireable = '" . convertBoolean($newHire) . "'";
	$hireable = $newHire;
}
if($newHire && $newHSD != $hire_sd) {
	$changes .= ', hire_price_sd = ' . $newHSD;
	$hire_sd = $newHSD;
}
if($newHire && $newHI != $hire_item) {
	$changes .= ', hire_price_item_id = ' . $newHI;
	$hire_item = $newHI;
}
if($newHire && $newHIA != $hire_amount) {
	$changes .= ', hire_price_item_amount = ' . $newHIA;
	$hire_amount = $newHIA;
}

if($newBreed != $breedable) {
	$changes .= ", is_breedable = '" . convertBoolean($newBreed) . "'";
	$breedable = $newBreed;
}
if($newBreed && $newBSD != $breed_sd) {
	$changes .= ', breeding_price_sd = ' . $newBSD;
	$breed_sd = $newBSD;
}
if($newBreed && $newBI != $breed_item) {
	$changes .= ', breeding_price_item_id = ' . $newBI;
	$breed_item = $newBI;
}
if($newBreed && $newBIA != $breed_amount) {
	$changes .= ', breeding_price_item_amount = ' . $newBIA;
	$breed_amount = $newBIA;
}

if($valid) {
	$notices[] = "Your squffy has been updated.";
	if(strlen($changes) > 0) {
		$query = 'UPDATE squffies SET '.substr($changes, 2) . ' WHERE squffy_id = ' . $squffy->getID();
		runDBQuery($query);
	}	
}
?>