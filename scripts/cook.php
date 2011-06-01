<?php
$valid = true;

$r_id = getID('recipe');
$batches = $_POST['batches'];
$id = getID('cook_id');
$inventory = $user->getInventory();

if($r_id < 1) {
	$errors[] = 'You must pick a recipe to cook.';
	$valid = false;	
} else {
	$recipe = Recipe::getRecipeByID($r_id);
	if($recipe == NULL) {
		$errors[] = 'You must pick a squffy to cook.';
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
				$error = 'You need ' . $amount . ' ' . pluralize(strtolower($ing['name'])) . ' to cook this recipe.';
				$errors[] = $error;
				$valid = false;
			}
		}
	}
}

if($id < 1) {
	$errors[] = 'You must pick a squffy to cook.';
	$valid = false;	
} else {
	$squffy = Squffy::getSquffyByID($id);
	if($squffy == NULL) {
		$errors[] = 'You must pick a squffy to cook.';
		$valid = false;	
	} else {
		if(!$squffy->isAbleToWork()) {
			$errors[] = 'That squffy is not able to cook right now.';
			$valid = false;	
		}
		if(!$squffy->canWorkFor($userid)) {
			$errors[] = 'That squffy is not able to cook for you right now.';
			$valid = false;	
		}
	}
}



if($valid) {	
	//Calculate the time finished
	$squffy->fetchDegree();
	if($squffy->getDegreeName() == "Cook") { $hours--; }
	$finished = time() + 60 * 60 * $hours;
	$date = date("Y-m-d H:i:s", $finished);
	
	//Create the job for the cron
	$query = "INSERT INTO jobs_cooking VALUES ($id, $userid, $r_id, '$date', $batches)";
	runDBQuery($query);
	
	//Update the worker
	$query = "UPDATE squffies SET is_working = 'true', energy = energy - $energy WHERE squffy_id = $id";
	runDBQuery($query);
	
	//Update the inventory
	foreach($ings as $ing) {
		$col = strtolower(str_replace(" ","_",$ing['name']));
		$change = -1 * $ing['amount'] * $batches;
		$user->updateInventory($col, $change, true);
	}
	
	//Display message
	$notices[] = $squffy->getName() . ' has started cooking a ' . $recipe->getName() . ' and will be done in ' . ($hours * $batches) . ' hours.';
}	
?>