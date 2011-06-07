<?php
$valid = true;
if(!isset($squffy) || $squffy == NULL) { die(); }
if(!isset($mate) || $mate == NULL) { die(); }

if($squffy->hasMate()) { 
	$errors[] = $squffy->getName() . " already has a mate."; 
	$valid = false;
}

if(!$squffy->isAdult()) {
	$errors[] = $squffy->getName() . " is not an adult yet.";
	$valid = false;
}

if($mate == NULL) { 
	$errors[] = "That mate does not exist.";
	$valid = false;
} else {
	if($mate->hasMate()) { 
		$errors[] = $mate->getName() . " already has a mate.";
		$valid = false;
	}
	
	if($squffy->getGender() == $mate->getGender()) {
		$errors[] = "These squffies are the same gender.";
		$valid = false;
	}
	
	if(!$mate->isAdult()) {
		$errors[] = $mate->getName() . " is not an adult yet.";
		$valid = false;
	}
}

if($valid) {
	if($acceptValidRequest || ($squffy->getOwnerID() == $mate->getOwnerID() && $squffy->getOwnerID() == $userid)) {
		$mate->setMate($squffy);
		$squffy->setMate($mate);
	} elseif($mate->getOwnerID() != $userid) {
		if(MatingNotification::requestExists($squffy, $mate)) {
			$errors[] = "You have sent this mating request already.";
		} else {
			MatingNotification::send($userid, $squffy->getID(), $mate);
		}
	} elseif($squffy->getOwnerID() != $userid) {
		if(MatingNotification::requestExists($squffy, $mate)) {
			$errors[] = "You have sent this mating request already.";
		} else {
			MatingNotification::send($userid, $mate->getID(), $squffy);
		}
	}
}
?>