<?php
$valid = true;
if(!isset($squffy)) {
	$id = getID("squffy_id");
	if($id < 1) {
		$errors[] = "This squffy does not exist.";
		$valid = false;
		$squffy = NULL;
	} else {
		$squffy = Squffy::getSquffyByID($id);
		if($squffy == NULL) {
			$errors[] = "This squffy does not exist.";
			$valid = false;
		}
	}
}

$degree = getID("degree_id");
if($degree < 1) {
	$errors[] = "This degree does not exist.";
	$valid = false;
}

if($squffy != NULL) {
	if(!$squffy->isAbleToLearn()) { 
		$errors[] = $squffy->getName() . " cannot go to school right now.";
		$valid = false;
	}
}

$teacher = NULL;
if(isset($_POST['teacher_id'])) {
	$teacher_id = getID("teacher_id");
	if($teacher_id < 1) {
		$errors[] = "That teacher does not exist.";
		$valid = false;
	} else {
		$teacher = Squffy::getSquffyByIDExtended($teacher_id, array(Squffy::FETCH_DEGREE));
		if($teacher == NULL) {
			$errors[] = "That teacher does not exist.";
			$valid = false;
		} else {
			if(!$teacher->isAbleToWork()) {
				$errors[] = "That teacher cannot work for you right now.";
				$valid = false;		
			}
		}
	}
}

$teaching = false;
if(isset($_POST['learn'])) {
	$days = 5;
	$inventory = $user->getInventory();
	if($inventory['pecan'] < 1) {
		$valid = false;
		$errors[] = "You cannot afford the pecan it costs to send your squffy to school.";
	}
	$teaching = true;

//Learn from teacher
} else if(isset($_POST['taught'])) {
	$days = 4;
	if($teacher == NULL) {
		$errors[] = "That teacher does not exist.";
		$valid = false;
	}
	if($teacher->getDegreeName() == "Teacher") { $days--; }
	if($teacher->hasStrength(Personality::TEACHING_TRAIT)) { $days--; }
	if($teacher->hasWeakness(Personality::TEACHING_TRAIT)) { $days++; }
} else {
	$errors[] = "Your request was sent improperly.";
	$valid = false;
}

if($valid) {
	$squffy->startDegree($degree, $days);
	$notices[] = $squffy->getName() . " has been sent to school.";
	if($teacher != NULL) {		
		$query = 'UPDATE `squffies` SET `is_working` = \'true\' WHERE `squffy_id` = ' . $teacher->getID();
	}
	if($teaching) {
		$user->updateInventory('pecan', -1, true);
	}
}	
?>