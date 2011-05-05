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
	if($squffy->isAbleToLearn()) { 
		$errors[] = $squffy->getName() . " cannot go to school right now.";
		$valid = false;
	}
}

$teacher = NULL;
if(isset($_POST['teacher_id'])) {
	$teacher_id = getID("teacher_id");
	if($teacher_id < 1) {
		$errors[] = "That teacher does not exist.1";
		$valid = false;
	} else {
		$teacher = Squffy::getSquffyByIDExtended($teacher_id, array(Squffy::FETCH_DEGREE));
		if($teacher == NULL) {
			$errors[] = "That teacher does not exist.2";
			$valid = false;
		} else {
			if(!$teacher->isAbleToWork($user)) {
				$errors[] = "That teacher cannot work for you right now.";
				$valid = false;		
			}
			if($teacher->getDegreeName() != "Teacher") {
				$errors[] = "That squffy is not qualified as a teacher.";
				$valid = false;		
			}
		}
	}
}

//TODO pay for schooling from Official School
if(isset($_POST['learn'])) {
	$days = 5;

} else if(isset($_POST['taught'])) {
	$days = 4;
	if($teacher == NULL) {
			$errors[] = "That teacher does not exist.3";
			$valid = false;
		}
} else {
	$errors[] = "Your request was sent improperly.";
	$valid = false;
}

if($valid) {
	$days = -1; //TODO change to actual values but testing is cool
	$squffy->startDegree($degree, $days);
	$notices[] = $squffy->getName() . " has been sent to school.";
	if($teacher != NULL) {
		//TODO set as working
	}
}	
?>