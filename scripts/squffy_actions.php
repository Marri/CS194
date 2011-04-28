<?php
if(!isset($squffy)) { die(); }

//Set mate
if(isset($_POST['set_mate'])) { 
	include('./scripts/squffy_mate.php'); 
}

//Breed
if(isset($_POST['breed'])) {
	include('./scripts/squffy_breed.php'); 
}

//Heal
if(isset($_POST['heal'])) {
	$original = $squffy->getHealth();
	if($original > 99) {
		$errors[] = $squffy->getName() . " does not need healing right now.";
	} else {
		$doctor_id = $_POST['doctor_id'];
		$doctor = Squffy::getSquffyByID($doctor_id);
		$squffy->heal($doctor);
		$change = $squffy->getHealth() - $original;
		$notices[] = pluralize($squffy->getName()) . " health has increased by $change.";
	}
}
?>