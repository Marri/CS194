<?php
//Set mate
if(isset($_POST['set_mate'])) {
	$valid = true;
	
	if($squffy->hasMate()) { 
		$errors[] = $squffy->getName() . " already has a mate."; 
		$valid = false;
	}
	
	$mate_id = $_POST['mate_id'];
	$mate = Squffy::getSquffyByID($mate_id);
	if($mate->hasMate()) { 
		$errors[] = $mate->getName() . " already has a mate.";
		$valid = false;
	}
	
	if($squffy->getGender() == $mate->getGender()) {
		$errors[] = "These squffies are the same gender.";
		$valid = false;
	}
	
	if($valid) {
		$mate->setMate($squffy);
		$squffy->setMate($mate);
	}
}

//Breed
if(isset($_POST['breed'])) {
	$valid = true;
	
	$mate_id = $_POST['mate_id'];
	$mate = Squffy::getSquffyByID($mate_id);
	if($squffy->getGender() == $mate->getGender()) {
		$errors[] = "These squffies are the same gender.";
		$valid = false;
	}
	
	if($squffy->getMateID() != $mate_id) {
		$errors[] = "Mated squffies can only breed to their mate.";
		$valid = false;
	}
	
	$male = NULL;
	$female = NULL;
	if($squffy->getGender() == 'M') {
		$male = $squffy;
		$female = $mate;
	} else {
		$female = $squffy;
		$male = $mate;
	}
	
	if($female->isPregnant()) { 
		$errors[] = $female->getName() . " is already pregnant.";
		$valid = false;
	}
	if($female->isSick()) { 
		$errors[] = $female->getName() . " is too sick.";
		$valid = false;
	}
	if($female->isWorking()) { 
		$errors[] = $female->getName() . " is currently working.";
		$valid = false;
	}
	if($female->isHungry()) { 
		$errors[] = $female->getName() . " is too hungry.";
		$valid = false;
	}
	if($female->isStudent()) { 
		$errors[] = $female->getName() . " is currently in school.";
		$valid = false;
	}
	
	if($valid) {
		$female->breedTo($male, $userid);
	}	
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