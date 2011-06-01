<?php
$selected = "squffies";

if(!isset($save_valid) || !$loggedin) {
	displayErrors(array("You have navigated to this page from the wrong place."));
	include('./includes/footer.php');
	die();
}

$name = $_POST['design_name'];
if(!$name) {
	$errors[] = "You did not pick a name.";
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
				$query = "INSERT INTO design_traits VALUES(IDHERE, $trait_id, '$color', $order)";
				$queries[] = $query;
			}
		}
	}
	
	if(sizeof($errors) < 1 && $numTraits > sizeof($queries)) {
		$errors[] = 'There was a problem reading all the appearance traits you picked.';
	}
}

$max = Design::MAX_NORMAL;
if($user->isUpgradedPlus()) { $max = Design::MAX_UPGRADE; }
$designs = Design::GetUserDesigns($userid);
if(sizeof($designs) == $max) {
	$errors[] = "You are only allowed $max saved designs. Please delete or overwrite unwanted designs instead.";
}

if(sizeof($errors) < 1) {
	if(isset($colors[$base])) { $base = $colors[$base]; }
	if(isset($colors[$eye])) { $eye = $colors[$eye]; }
	if(isset($colors[$feet])) { $feet = $colors[$feet]; }

	$query = "SELECT species_id FROM species WHERE species_name = '$species'";
	$result = runDBQuery($query);
	$info = @mysql_fetch_assoc($result);
	$species_id = $info['species_id'];

	$query = "INSERT INTO designs VALUES (NULL, '$name', $userid, $species_id, '$base', '$eye', '$feet', $numTraits);";
	runDBQuery($query);
	$id = mysql_insert_id();
	
	if($numTraits > 0) {
		foreach($queries as $query) {
			$query = str_replace("IDHERE", $id, $query);
			runDBQuery($query);
		}
	}
	$notices[] = 'The following design has been successfully saved.';
	displayNotices($notices);
	echo '<br /><b>Design</b>: ' . $name . '<br />' . generateImage(); 
	//TODO save design image
	include('./includes/footer.php');
	die();
}
?>