<?php
$valid = true;

$work = $_POST['worker'];
if(!Verify::VerifyID($work)) {
	$errors[] = 'You picked a worker that does not exist.';
	$valid = false;
} else {
	$worker = Squffy::getSquffyByID($work);
	if($worker == NULL) {
		$errors[] = 'You picked a worker that does not exist.';
		$valid = false;
	} elseif($worker->getEnergy() < 5) {
		$errors[] = $worker->getName() . ' does not have enough energy.';
		$valid = false;
	} elseif(!$worker->isAbleToWork(5)) {
		$errors[] = $worker->getName() . ' cannot work right now.';
		$valid = false;
	} elseif(!$worker->canWorkFor($userid)) {
		$errors[] = $worker->getName() . ' cannot work for you right now.';
		$valid = false;
	}
}

if($farm->getNumWorkers() > 0) {
	$errors[] = 'This farm is already being tended.';
	$valid = false;
}

if($valid) {	
	//Find time done
	$minutes = 30;
	$finished = time() + 60 * $minutes;
	$date = date("Y-m-d H:i:s", $finished);
	
	//Insert jobs
	$query = "INSERT INTO jobs_farming VALUES ($work, $id, 'Weed', '$date')";
	runDBQuery($query);
	
	//Workers are working
	$query = "UPDATE squffies SET is_working = 'true', energy = energy - 5 WHERE squffy_id = $work";
	runDBQuery($query);
	
	//Farm has workers
	$query = "UPDATE farms SET num_workers = 1 WHERE farm_id = $id";
	runDBQuery($query);
	
	$notices[] = 'Your farm is now being weeded.';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>