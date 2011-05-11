<?php
include("../includes/connect.php");
include("../objects/appearance.php");

//Get species info
$species = isset($_GET['species']) ? $_GET['species'] : 'tree';
$queryString = "SELECT species_name FROM `species` WHERE `design_activated` = 'true';";
$query = runDBQuery($queryString);
$valid_species = false;
while($breed = @mysql_fetch_assoc($query)) {
	if(strtolower($breed['species_name']) == $species) { $valid_species = true; }
}
if(!$valid_species) { $species = 'tree'; }

//Get basic info
$base = getColor('base', Appearance::BASE_DEFAULT);
$eye = getColor('eye', Appearance::EYE_DEFAULT);
$foot = getColor('feetEar', Appearance::FOOT_DEFAULT);

//Get traits
$numTraits = (isset($_GET['numTraits']) ? $_GET['numTraits'] : 0);
$markings = array();
$mutations = array();

if($numTraits > 0) {
	$query = 'SELECT trait_name, trait_type FROM appearance_traits';
	$result = runDBQuery($query);
	$traits = array();
	while($t = @mysql_fetch_assoc($result)) {
		$traits[$t['trait_name']] = $t['trait_type'];
	}
	
	for($i = 1; $i <= $numTraits; $i++) {
		$trait = getTrait('trait' . $i, $traits);
		if($trait == NULL) { continue; }
		if($trait['isMark']) { $markings[] = $trait; }
		else { $mutations[] = $trait; }
	}
}

$markings = array_reverse($markings);
$mutations = array_reverse($mutations);

function getTrait($key, &$traits, $default = 'FFFFFF') {
	$name = strtolower($_GET[$key]);
	$mark = true;
	if(substr($name, 0, 3) == "mut") {
		$mark = false;
		$name = substr($name, 3);
		if(!isset($traits[$name])) { return NULL; }
		if($traits[$name] != 2) { return NULL; }
	} else {
		if(!isset($traits[$name])) { return NULL; }
		if($traits[$name] != 1) { return NULL; }
	}
	
	$color = getColor($key, $default);
	
	return array('name' => $name, 'color' => $color, 'isMark' => $mark);
}

function getColor($key, $default) {
	$color = $default;
	$colors = Appearance::Colors();
	if(isset($_GET[$key . 'Color'])) { $color = $_GET[$key . 'Color']; }
	if(isset($colors[$color])) { $color = $colors[$color]; }
	if(!isValidHex($color)) { $color = $default; }
	return $color;
}

function isValidHex($hex) {
	$len = strlen($hex);
	if($len != 6) { return false; }
	if(strlen(preg_replace('/[a-fA-F0-9]+/', "", $hex)) > 0) { return false; }
	return true;
};

include('./generate_image.php');

?>