<?php
include('../includes/connect.php');
$queryString = "SELECT * FROM species";
$query = runDBQuery($queryString);
$species = array();
while($s = @mysql_fetch_assoc($query)) { 
	$species[] = strtolower($s['species_name']);
}

$queryString = "SELECT * FROM `appearance_traits` ORDER BY `trait_title` ASC;";
$query = runDBQuery($queryString);
while($trait = @mysql_fetch_assoc($query)) {
	$name = $trait['trait_name'];
	if($trait['trait_type'] == 1) { checkIfExists($name, $species); }
	else {
		checkIfExists($name . 'c', $species);
		checkIfExists($name . 'l', $species);
		checkIfNotExists($name, $species);
	}
}

function checkIfNotExists($name, $species) {
	foreach($species as $s) {
		$img = "../images/generate/$s/$s" . "adult$name" . "h.png";
		if(file_exists($img)) {
			echo 'Need to combine ' . $name . ' for ' . $s . '<br>';
		}
	}
}

function checkIfExists($name, $species) {
	foreach($species as $s) {
		$img = "../images/generate/$s/$s" . "adult$name.png";
		if(!file_exists($img)) {
			echo 'Missing ' . $name . ' for ' . $s . '<br>';
		}
	}
}
?>