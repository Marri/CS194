<?php
include('../includes/connect.php');
$objects = array('user', 'appearance', 'cost', 'design', 'forums', 'item', 'lot', 'messaging', 'notification', 'personality', 'squffy', 'food', 'recipe');
foreach($objects as $object) {
	include('../objects/' . $object . '.php');
}
$queryString = "SELECT * FROM species";
$query = runDBQuery($queryString);
$species = array();
while($s = @mysql_fetch_assoc($query)) { 
	$species[] = strtolower($s['species_name']);
}

$age = 'adult';
$queryString = "SELECT * FROM `appearance_traits` ORDER BY `trait_name` ASC;";
$query = runDBQuery($queryString);
while($trait = @mysql_fetch_assoc($query)) {
	$name = $trait['trait_name'];
	if($trait['trait_type'] == 1) { 
		checkIfExists($name, $species, $age); 
	} else {
		checkIfExists($name . 'c', $species, $age);
		checkIfExists($name . 'l', $species, $age);
	}
}

$ages = array('adult', 'child', 'hatchling');
foreach($ages as $age) {
	checkIfExists('base', $species, $age);
	checkIfExists('eye', $species, $age);
	checkIfExists('feetear', $species, $age);
	checkIfExists('standard', $species, $age);
}

function checkIfExists($name, $species, $age) {
	foreach($species as $s) {
		$img = "../images/generate/$s/$s" . $age . "$name.png";
		if(!file_exists($img)) {
			echo 'Missing ' . $name . ' for ' . $s . ' ' . $age . '<br>';
		}
	}
}

/*reset all
function resetAll() {
	$squffies = Squffy::getSquffies("SELECT * FROM squffies");
	foreach($squffies as $squffy) {
		include('./reset_image.php');
	}
}*/

/*
$objects = array('user', 'appearance', 'cost', 'design', 'forums', 'item', 'lot', 'messaging', 'notification', 'personality', 'squffy');
foreach($objects as $object) {
	include('../objects/' . $object . '.php');
}

$squffy = Squffy::getSquffyByID(2);
$squffy->getThumbnail();

/*$queryString = "SELECT * FROM species";
$query = runDBQuery($queryString);
$species = array();
while($s = @mysql_fetch_assoc($query)) { 
	$species[] = strtolower($s['species_name']);
}
$ages = array('child', 'adult', 'hatchling');

$queryString = "SELECT * FROM `appearance_traits` ORDER BY `trait_title` ASC;";
$query = runDBQuery($queryString);
while($trait = @mysql_fetch_assoc($query)) {
	$name = $trait['trait_name'];
	foreach($ages as $age) {
		if($trait['trait_type'] == 1) { 
			checkIfExists($name, $species, $age); 
		} else {
			checkIfExists($name . 'c', $species, $age);
			checkIfExists($name . 'l', $species, $age);
			checkIfNotExists($name, $species, $age);
		}
	}
}

function checkIfNotExists($name, $species, $age) {
	foreach($species as $s) {
		$img = "../images/generate/$s/$s" . $age . $name . "h.png";
		if(file_exists($img)) {
			echo 'Need to combine ' . $name . ' for ' . $s . '<br>';
			$img = "../images/generate/$s/$s" . $age . $name . "l.png";
			echo '<img src="' . $img . '" /><br />';
			fixImage($name, $s);
		}
	}
}

function fixImage($name, $s) {
	$location = "../images/generate/$s/$s" . "adult$name" . "h.png";
	$img = imagecreatefrompng($location);
	imagesavealpha($img, true);
	$image_width = imagesx($img);
	$image_height = imagesy($img);
	$truecolor = imagecreatetruecolor($image_width, $image_height);
	$truecolor = $img; //truecolor is the highlights
	
	$location = "../images/generate/$s/$s" . "adult$name" . "l.png";
	$img = imagecreatefrompng($location);
	imagesavealpha($img, true);
	$image_width = imagesx($img);
	$image_height = imagesy($img);
	$lines = imagecreatetruecolor($image_width, $image_height);
	$lines = $img; //truecolor is the highlights
	
	imagecopy($truecolor, $lines, 0, 0, 0, 0, $image_width, $image_height);
	imagepng($truecolor, "../images/generate/$s/$s" . "adult$name" . "l.png");
}

function checkIfExists($name, $species) {
	foreach($species as $s) {
		$img = "../images/generate/$s/$s" . "adult$name.png";
		if(!file_exists($img)) {
			echo 'Missing ' . $name . ' for ' . $s . '<br>';
		}
	}
}*/
?>