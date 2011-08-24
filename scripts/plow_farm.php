<?php
$valid = true;

$workers = $_POST['worker'];
if(sizeof($workers) < 1) {
	$errors[] = 'You must choose at least one worker.';
	$valid = false;
} elseif(sizeof($workers) > 4) {
	$errors[] = 'You don\'t need more than four workers.';
	$valid = false;
} else {
	$worker_squffies = array();
	foreach($workers as $work) {
		if(!Verify::VerifyID($work)) {
			$errors[] = 'You picked a worker that does not exist.';
			$valid = false;
		} else {
			$worker = Squffy::getSquffyByID($work);
			if($worker == NULL) {
				$errors[] = 'You picked a worker that does not exist.';
				$valid = false;
			} elseif($worker->getEnergy() < 10) {
				$errors[] = $worker->getName() . ' does not have enough energy.';
				$valid = false;
			} elseif(!$worker->isAbleToWork(10)) {
				$errors[] = $worker->getName() . ' cannot work right now.';
				$valid = false;
			} elseif(!$worker->canWorkFor($userid)) {
				$errors[] = $worker->getName() . ' cannot work for you right now.';
				$valid = false;
			} else {
				$worker_squffies[] = $worker;
			}
		}
	}
}

if($user->getAmount('hoe') < 1) {
	$errors[] = 'You need a hoe to plow your fields.';
	$valid = false;
}

if($farm->getNumWorkers() > 0) {
	$errors[] = 'This farm is already being tended.';
	$valid = false;
}

if($valid) {
	//Hoe is in use
	$user->updateInventory('hoe', -1, true);
	
	//Find time done
	$minutes = (4 * 60) / sizeof($worker_squffies);
	$finished = time() + 60 * $minutes;
	$date = date("Y-m-d H:i:s", $finished);
	
	//Insert jobs
	$ids = '';
	foreach($worker_squffies as $squffy) { 
		$ids .= ', '.$squffy->getID();
		$sid = $squffy->getID();
		$query = "INSERT INTO jobs_farming VALUES ($sid, $id, 'Plow', '$date')";
		runDBQuery($query);
	}
	
	//Workers are working
	$query = "UPDATE squffies SET is_working = 'true', energy = energy - 10 WHERE squffy_id IN (" . substr($ids, 2) . ")";
	runDBQuery($query);
	
	//Farm has workers
	$num = sizeof($worker_squffies);
	$query = "UPDATE farms SET num_workers = $num WHERE farm_id = $id";
	runDBQuery($query);
	
	$notices[] = 'Your farm is now being plowed.';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>