<?php
$valid = true;
$chargeWalnut = false;
if(!isset($squffy)) { die(); }

$mate_id = getID("parent_id");
if($mate_id == 0) {
	$errors[] = "This squffy does not exist.";
	$valid = false;
} elseif ($mate_id == $id) {
	$errors[] = "This is the same squffy.";
	$valid = false;
} else {
	$mate = Squffy::getSquffyByID($mate_id);
	if($mate == NULL) {
		$errors[] = "This squffy does not exist.";
		$valid = false;
	} else {
		if($squffy->getGender() == $mate->getGender()) {
			$errors[] = "These squffies are the same gender.";
			$valid = false;
		}
		
		if($squffy->hasMate() && $squffy->getMateID() != $mate_id) {
			$errors[] = "Mated squffies can only breed to their mate.";
			$valid = false;
		}
		
		if($mate->hasMate() && $mate->getMateID() != $id) {
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
		if(!$female->isAdult()) {
			$errors[] = $female->getName() . " is too young.";
			$valid = false;
		}
		
		if($male->isSick()) { 
			$errors[] = $male->getName() . " is too sick.";
			$valid = false;
		}
		if($male->isWorking()) { 
			$errors[] = $male->getName() . " is currently working.";
			$valid = false;
		}
		if($male->isHungry()) { 
			$errors[] = $male->getName() . " is too hungry.";
			$valid = false;
		}
		if($male->isStudent()) { 
			$errors[] = $male->getName() . " is currently in school.";
			$valid = false;
		}
		if(!$male->isAdult()) {
			$errors[] = $male->getName() . " is too young.";
			$valid = false;
		}
		
		if(!$male->canBreedFor($userid)) {
			$errors[] = 'You do not have permission to breed to ' . $male->getName(). ".";
			$valid = false;
		}
		
		if(!$female->canBreedFor($userid)) {
			$errors[] = 'You do not have permission to breed to ' . $female->getName(). ".";
			$valid = false;
		}
		
		if(!($male->hasMate() && $male->getMateID() == $female->getID())) {
			$chargeWalnut = true;
			$inventory = $user->getInventory();
			if($inventory['walnut'] < 1) {
				$errors[] = 'You need a walnut to breed unmated squffies.';
				$valid = false;
			}
		}
	}
}

if($valid) {
	$female->breedTo($male, $userid);
	if($chargeWalnut) { $user->updateInventory('walnut', -1, true); }
	$notices[] = "Congratulations! " . $male->getName() . " and " . $female->getName() . " are about to be parents.";
}	
?>