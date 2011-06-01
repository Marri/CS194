<?php
$selected = "squffies";
$forLoggedIn = true;

if(!isset($save_valid)) {
	displayErrors(array("You have navigated to this page from the wrong place."));
	include('./includes/footer.php');
	die();
}

$id = getID('design_id');
if($id < 1) { $errors[] = "You did not pick a design to overwrite."; }
else { 
	$design = Design::getDesignByID($id); 
	if($design->getUser() != $userid) { $errors[] = "This is not your design!"; }
}

if(isset($_POST['species'])) { $species = $_POST['species']; }
else { $errors[] = "You did not pick a species."; }

if(isset($_POST['base_color']) && $_POST['base_color']) { $base = $_POST['base_color']; }
else { $errors[] = "You did not pick a base color."; }

if(isset($_POST['eye_color']) && $_POST['eye_color']) { $eye = $_POST['eye_color']; }
else { $errors[] = "You did not pick an eye color."; }

if(isset($_POST['feet_ear_color']) && $_POST['feet_ear_color']) { $feet = $_POST['feet_ear_color']; }
else { $errors[] = "You did not pick a foot and ear color."; }

$numTraits = (isset($_POST['numTraits']) ? $_POST['numTraits'] : 0);

$colors = Appearance::Colors();

if($numTraits > 0) {
	$query = 'SELECT trait_name, trait_id FROM appearance_traits';
	$result = runDBQuery($query);
	$traits = array();
	while($t = @mysql_fetch_assoc($result)) {
		$traits[$t['trait_name']] = $t['trait_id'];
	}
	
	$queries = array();
	for($i = 1; $i <= $numTraits; $i++) {
		if(!isset($_POST['trait' . $i])) { $errors[] = 'You did not pick one of your appearance traits.'; }
		else {
			$trait = $_POST['trait' . $i];
			if(substr($trait, 0, 3) == "mut") { $trait = substr($trait, 3); }
			
			if(!isset($_POST['trait' . $i . '_color']) || !$_POST['trait' . $i . '_color']) { $errors[] = 'You did not pick a color for one of your appearance traits.'; }
			else {
				$color = $_POST['trait' . $i . '_color'];
				if(isset($colors[$color])) { $color = $colors[$color]; }
				$order = $i - 1;
				$trait_id = $traits[$trait];
				$query = "INSERT INTO design_traits VALUES($id, $trait_id, '$color', $order)";
				$queries[] = $query;
			}
		}
	}
	
	if(sizeof($errors) < 1 && $numTraits > sizeof($queries)) {
		$errors[] = 'There was a problem reading all the appearance traits you picked.';
	}
}

if(sizeof($errors) < 1) {
	if(isset($colors[$base])) { $base = $colors[$base]; }
	if(isset($colors[$eye])) { $eye = $colors[$eye]; }
	if(isset($colors[$feet])) { $feet = $colors[$feet]; }

	$query = "SELECT species_id FROM species WHERE species_name = '$species'";
	$result = runDBQuery($query);
	$info = @mysql_fetch_assoc($result);
	$species_id = $info['species_id'];

	$query = "UPDATE designs 
	SET species_id = $species_id, base_color = '$base', eye_color = '$eye', foot_color = '$feet', num_traits = '$numTraits' 
	WHERE design_id = $id";
	runDBQuery($query);
	
	$query = "DELETE FROM design_traits WHERE design_id = $id";
	runDBQuery($query);
	if($numTraits > 0) {
		foreach($queries as $query) {
			runDBQuery($query);
		}
	}
	$notices[] = 'The following design has been successfully saved.';
	displayNotices($notices);
	//TODO save design image
	echo '<br /><b>Design</b>: ' . $design->getName() . '<br />' . generateImage(); 
	include('./includes/footer.php');
	die();
}
?>