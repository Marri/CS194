<?php
$selected = "squffies";
$forLoggedIn = true;
include("./includes/header.php");

if(!isset($save_valid)) {
	displayErrors(array("You have navigated to this page from the wrong place."));
	include('./includes/footer.php');
	die();
}
$name = $_POST['design_name'];
if(!$name) {
	$errors[] = "You did not pick a name.";
}

if(isset($_POST['species'])) { $speces = $_POST['species']; }
if(isset($_POST['base_color'])) { $base = $_POST['base_color']; }
if(isset($_POST['eye_color'])) { $eye = $_POST['eye_color']; }
if(isset($_POST['feet_ear_color'])) { $feet = $_POST['feet_ear_color']; }
$numTraits = (isset($_POST['numTraits']) ? $_POST['numTraits'] : 0);

$colors = Appearance::Colors();
if(isset($colors[$base])) { $base = $colors[$base]; }
if(isset($colors[$eye])) { $eye = $colors[$eye]; }
if(isset($colors[$feet])) { $feet = $colors[$feet]; }

$species = $_POST['species'];
$query = "SELECT species_id FROM species WHERE species_name = '$species'";
$result = runDBQuery($query);
$info = @mysql_fetch_assoc($result);
$species_id = $info['species_id'];

$query = "INSERT INTO designs VALUES (NULL, '$name', $userid, $species_id, '$base', '$eye', '$feet', $numTraits);";
runDBQuery($query);
$id = mysql_insert_id();

if($numTraits > 0) {
	$query = 'SELECT trait_name, trait_id FROM appearance_traits';
	$result = runDBQuery($query);
	$traits = array();
	while($t = @mysql_fetch_assoc($result)) {
		$traits[$t['trait_name']] = $t['trait_id'];
	}
	
	for($i = 1; $i <= $numTraits; $i++) {
		$trait = $_POST['trait' . $i];
		$color = $_POST['trait' . $i . '_color'];
		if(isset($colors[$color])) { $color = $colors[$color]; }
		$order = $i - 1;
		if(substr($trait, 0, 3) == "mut") { $trait = substr($trait, 3); }
		$trait_id = $traits[$trait];
		$query = "INSERT INTO design_traits VALUES($id, $trait_id, '$color', $order)";
		runDBQuery($query);
	}
}

$notices[] = 'The following design has been successfully saved.';

displayNotices($notices);
displayErrors($errors);
echo '<br /><b>Design</b>: ' . $name . '<br />' . generateImage(); 

include('./includes/footer.php');
?>