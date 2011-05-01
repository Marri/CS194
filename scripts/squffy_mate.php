<?php
$valid = true;
if(!isset($squffy) || $squffy == NULL) { die(); }

if($squffy->hasMate()) { 
	$errors[] = $squffy->getName() . " already has a mate."; 
	$valid = false;
}

if(!$squffy->isAdult()) {
	$errors[] = $squffy->getName() . " is not an adult yet.";
	$valid = false;
}

$mate_id = $_POST['mate_id'];
$mate = Squffy::getSquffyByID($mate_id);
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

//TODO: require approval from other users
if($valid) {
	$mate->setMate($squffy);
	$squffy->setMate($mate);
}
?>