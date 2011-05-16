<?php
$valid = true;
if(!isset($squffy) || $squffy == NULL) { die(); }
/*
if($squffy->hasMate()) { 
	$errors[] = $squffy->getName() . " already has a mate."; 
	$valid = false;
}
*/
$newTraits = array();
for($j = 0; $j < $num; $j++) {
	$newTraits[$j] = NULL;
}

$queries = array();
for($j = 0; $j < $num; $j++) {
	if(!isset($_POST['trait'.$j])) {
		$errors[] = 'You must pick a trait for layer '. $j;
		$valid = false;
		break;
	}
	if($newTraits[$j] != NULL) {
		$errors[] = 'You must pick only one trait for layer ' . ($j + 1);
		$valid = false;
		break;
	}
	$newTrait = $_POST['trait'.$j];
	$newTraits[$j] = $newTrait;
	if(!array_key_exists($newTrait, $t)) {
		$errors[] = 'You can only change the order of traits ' . $squffy->getName() . ' has.';
		$valid = false;
		break;
	}
	if($t[$newTrait]->getSquare() == 'C') {
		$errors[] = "You cannot change the order of recessive (carried, not visible) appearance traits.";
		$valid = false;
		break;
	}
	if($t[$newTrait]->getOrder() != $j) {
		$queries[] = "UPDATE squffy_appearance SET trait_order = '$j' WHERE trait_id = '$newTrait' AND squffy_id = '$id'";
	}
}

if($valid) {
	foreach($queries as $query) { 
		runDBQuery($query);
	}
	$notices[] = "Your squffy's traits have been successfully reordered!<br />Their image has been automatically updated. You may need to press Ctrl+F5 to see the changes.";
	$squffy->refetchAppearance();
	$t = $squffy->getAppearanceTraits();
	$traits = array();
	foreach($t as $trait) {
		if($trait->getSquare() != 'C') { $traits[] = $trait; }
	}
}
?>