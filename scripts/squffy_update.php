<?php
$valid = true;
$changes = "";
if(!isset($squffy)) { die(); }

$newName = $_POST['squffy_name'];
$newHire = $_POST['hireable'] == 'y' ? true : false;
$newBreed = $_POST['breedable'] == 'y' ? true : false;

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
	$changes .= ", hireable = '" . convertBoolean($newHire) . "'";
	$hireable = $newHire;
}
if($newBreed != $breedable) {
	$changes .= ", breedable = '" . convertBoolean($newBreed) . "'";
	$breedable = $newBreed;
}

if($valid) {
	$notices[] = "Your squffy has been updated.";
}
if(strlen($changes) > 0) {
	$query = 'UPDATE squffies SET '.substr($changes, 2) . ' WHERE squffy_id = ' . $squffy->getID();
}	
?>