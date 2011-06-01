<?php
$valid = true;

$r_id = getID('recipe');
$batches = $_POST['batches'];
$id = getID('carpenter_id');
$inventory = $user->getInventory();

if($r_id < 1) {
	$errors[] = 'You must pick an item to carve.';
	$valid = false;	
} else {
	$recipe = Recipe::getRecipeByID($r_id);
	if($recipe == NULL) {
		$errors[] = 'You must pick a squffy to carve.';
		$valid = false;	
	} else {
		$recipe->fetchNames();
		$hours = $recipe->getTime();
		$energy = $recipe->getEnergy();
		$ings = $recipe->getIngredients();
		
		foreach($ings as $ing) {
			$col = strtolower(str_replace(" ","_",$ing['name']));
			$amount = $ing['amount'] * $batches;
			if($inventory[$col] < $amount) {
				$error = 'You need ' . $amount . ' ' . pluralize(strtolower($ing['name'])) . ' to carve this item.';
				$errors[] = $error;
				$valid = false;
			}
		}
	}
}

if($id < 1) {
	$errors[] = 'You must pick a squffy to carve.';
	$valid = false;	
} else {
	$squffy = Squffy::getSquffyByID($id);
	if($squffy == NULL) {
		$errors[] = 'You must pick a squffy to carve.';
		$valid = false;	
	} else {
		if(!$squffy->isAbleToWork()) {
			$errors[] = 'That squffy is not able to carve right now.';
			$valid = false;	
		}
		if(!$squffy->canWorkFor($userid)) {
			$errors[] = 'That squffy is not able to carve for you right now.';
			$valid = false;	
		}
	}
}

if($squffy != NULL && $recipe != NULL) {
	if($squffy->getEnergy() < $energy * $batches) {
		$errors[] = 'That squffy does not have enough energy to finish this job.';
		$valid = false;	
	}
}

if($valid) {	
	//Calculate the time finished
	$squffy->fetchDegree();
	if($squffy->getDegreeName() == "Carpenter") { $hours--; }
	$finished = time() + 60 * 60 * $hours * $batches;
	$date = date("Y-m-d H:i:s", $finished);
	
	//Create the job for the cron
	$query = "INSERT INTO jobs_cooking VALUES ($id, $userid, $r_id, '$date', $batches)";
	runDBQuery($query);
	
	//Update the worker
	$energy *= $batches;
	$query = "UPDATE squffies SET is_working = 'true', energy = energy - $energy WHERE squffy_id = $id";
	runDBQuery($query);
	
	//Update the inventory
	foreach($ings as $ing) {
		$col = strtolower(str_replace(" ","_",$ing['name']));
		$change = -1 * $ing['amount'] * $batches;
		$user->updateInventory($col, $change, true);
	}
	
	//Display message
	$notices[] = $squffy->getName() . ' has started carving a ' . $recipe->getName() . ' and will be done in ' . ($hours * $batches) . ' hours.';
}	
?>