<?php
if(!isset($squffy)) { die(); }

//Update information
if(isset($_POST['update_squffy'])) {
	include('./scripts/squffy_update.php'); 
}

//Set mate
if(isset($_POST['set_mate'])) { 
	include('./scripts/squffy_mate.php'); 
}

//Breed
if(isset($_POST['breed'])) {
	include('./scripts/squffy_breed.php'); 
}

//Buy breed rights
if(isset($_POST['buy_breed'])) {
	include('./scripts/squffy_breed_rights.php');
}

//Buy hire rights
if(isset($_POST['buy_hire'])) {
	include('./scripts/squffy_hire_rights.php');
}

//Learn from teacher
if(isset($_POST['taught'])) {
	$days = 4;
	include('./scripts/squffy_learn.php'); 
}

//Reorder appearance traits
if(isset($_POST['reorder'])) {
	include('./scripts/squffy_reorder.php'); 
	include('./scripts/reset_image.php'); 
}

//Reset image
if(isset($_POST['reset_image'])) {
	include('./scripts/reset_image.php');
	$notices[] = "Your squffy's image has been reset. You may need to press Ctrl+F5 to see the changes."; 
}

//Heal
if(isset($_POST['heal'])) {
	$original = $squffy->getHealth();
	if($original > 99) {
		$errors[] = $squffy->getName() . " does not need healing right now.";
	} else {
		$doctor_id = $_POST['doctor_id'];
		$doctor = Squffy::getSquffyByID($doctor_id);
		if($doctor->getEnergy() < 5) { 
			$errors[] = $doctor->getName() . " must have at least 5 energy to heal.";
		} else {
			$squffy->heal($doctor);
			$change = $squffy->getHealth() - $original;
			$notices[] = pluralize($squffy->getName()) . " health has increased by $change.";
		}
	}
}

//Feed
if(isset($_POST['feed'])) {
	$original = $squffy->getHunger();
	if($original < 1) {
		$errors[] = $squffy->getName() . " does not need feeding right now.";
	} else {
		$food = $_POST['food_id'];
		$item = Item::getItemByID($food);
		$inventory = $user->getInventory();
		$col = $item->getColumnName();
		
		if($inventory[$col] < 1) {
			$errors[] = 'You do not have enough ' . strtolower($item->getName()) . 's.';
		} else {
			$old = $squffy->getHunger();
			$squffy->feed($item);
			$user->updateInventory($col, -1, true);
			$change = $old - $squffy->getHunger();
			$notices[] = pluralize($squffy->getName()) . ' hunger has decreased by ' . $change . '.';
		}
	}
}
?>