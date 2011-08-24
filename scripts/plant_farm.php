<?php
$valid = true;

$workers = $_POST['worker'];
if(sizeof($workers) < 1) {
	$errors[] = 'You must choose at least one worker.';
	$valid = false;
} elseif(sizeof($workers) > 4) {
	$errors[] = 'You don\'t need more than two workers.';
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

$num_seeds = $_POST['num_seeds'];
if(!Verify::VerifyID($num_seeds)) {
	$errors[] = 'You need to plant at least one bag of seeds.';
	$valid = false;
} elseif($num_seeds < 1) {
	$errors[] = 'You need to plant at least one bag of seeds.';
	$valid = false;
} elseif($num_seeds > 5) {
	$errors[] = 'Your field can only contain up to five bags of seed.';
	$valid = false;
}

$seed_id = $_POST['seed'];
if(!Verify::VerifyID($seed_id)) {
	$errors[] = 'You need to pick a type of seed.';
	$valid = false;
} else {
	$seed = Item::getItemByID($seed_id);
	if($seed == NULL) {
		$errors[] = 'You need to pick a type of seed.';
		$valid = false;
	} elseif(!$seed->isSeed()) { 
		$errors[] = 'You need to pick a type of seed.';
		$valid = false;
	} elseif($user->getAmount($seed->getColumnName()) < 1) { 
		$errors[] = 'You need to pick a seed you own.';
		$valid = false;
	}
}

if($farm->getNumWorkers() > 0) {
	$errors[] = 'This farm is already being tended.';
	$valid = false;
}

if($valid) {
	//Hoe is in use
	$user->updateInventory($seed->getColumnName(), -1, true);
	
	//Find time done
	$minutes = (4 * 60) / sizeof($worker_squffies);
	$finished = time() + 60 * $minutes;
	$date = date("Y-m-d H:i:s", $finished);
	
	//Insert jobs
	$ids = '';
	foreach($worker_squffies as $squffy) { 
		$ids .= ', '.$squffy->getID();
		$sid = $squffy->getID();
		$query = "INSERT INTO jobs_farming VALUES ($sid, $id, 'Plant', '$date')";
		runDBQuery($query);
	}
	
	//Workers are working
	$query = "UPDATE squffies SET is_working = 'true', energy = energy - 10 WHERE squffy_id IN (" . substr($ids, 2) . ")";
	runDBQuery($query);
	
	//Farm has workers
	$num = sizeof($worker_squffies);
	$query = "UPDATE farms SET num_workers = $num, food_id = $seed_id, num_crops = $num_seeds WHERE farm_id = $id";
	runDBQuery($query);
	
	$notices[] = 'Your farm is now being planted.';
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>