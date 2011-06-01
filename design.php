<?php
$selected = "squffies";
$js[] = "design";
$js[] = "colorpicker";
$css[] = "design";
$css[] = "colorpicker";
include("./includes/header.php");

if(isset($_POST['save'])) {
	$save_valid = true;
	include('./scripts/save_design.php');
}

if(isset($_POST['overwrite'])) {
	$save_valid = true;
	include('./scripts/overwrite_design.php');
}

displayColors();

$traitlist = trait_options($con);

displayNotices($notices);
displayErrors($errors);

$design_id = getID('design');
$design = NULL;
if($design_id > 0) {
	$design = Design::getDesignByID($design_id);
	$design->fetchSpecies();
	$design->fetchTraits();
}

$species = "tree";
if(isset($_POST['species'])) { $species = $_POST['species']; }
elseif($design != NULL) { $species = strtolower($design->getSpeciesName()); }

$base = "F0DDA3";
if(isset($_POST['base_color'])) { $base = $_POST['base_color']; }
elseif($design != NULL) { $base = $design->getBase(); }

$eye = "green";
if(isset($_POST['eye_color'])) { $eye = $_POST['eye_color']; }
elseif($design != NULL) { $eye = $design->getEye(); }

$foot = "brown";
if(isset($_POST['feet_ear_color'])) { $foot = $_POST['feet_ear_color']; }
elseif($design != NULL) { $foot = $design->getFoot(); }

$numTraits = 0;
if(isset($_POST['numTraits'])) { $numTraits = $_POST['numTraits']; }
elseif($design != NULL) { $numTraits = $design->getNumTraits(); }
if($numTraits < 1) { $numTraits = 1; }

?>
<form action="./design.php" method="post" name="design_form">
<table cellspacing="0" class="content-table" id="contentTable">
	<tr><td class="width150"></td><td class="width150"></td><td class="width150"></td><td class="width150"></td></tr>
	<tr>
    	<th class="content-header" colspan="4">Design Custom Squffy</th>
	</tr>
	<tr>
    	<th colspan="3" class="content-subheader width450">Preview Design</th>
        <th class="content-subheader width150">Instructions</th>
	</tr>
    <tr>
    	<td colspan="3" class="width450 text-center">
            <?php echo generateImage($design); ?>			
        </td>
        <td class="vertical-top small width150">
            Add as many traits as you like; trait 1 appears on top of trait 2, etc.<br /><br />
            To choose a color:<br /><br />
            1. Type a hex color in the text box (ex: FF91EF or 028319)<br /><br />
            2. Type the name of a color in the text box (<a href='#' id='showColorList'>Full list</a>)<br /><br />
            3. Click the color box and choose a color from the slider
		</td>
	</tr>
    <tr>
        <th class="content-subheader width150">Species</th>
        <th class="content-subheader width150">Base Color</th>
    	<th class="content-subheader width150">Eye Color</th>
        <th class="content-subheader width150">Feet & Ear Color</th>
	</tr>
	<tr>
    	<?php
        echo '<td align="center" class="vertical-top width150">
			<input type="hidden" value="' . $numTraits . '" name="numTraits" />
        	<select name="species" size="1" class="width125">';
				$queryString = "SELECT * FROM `species` WHERE `design_activated` = 'true';";
				$query = runDBQuery($queryString);
				while($spec = @mysql_fetch_assoc($query)) {
					echo '<option value="' . strtolower($spec['species_name']) . '"';
					if($species == strtolower($spec['species_name'])) { echo ' selected'; }
					echo '>' . $spec['species_name'] . '</option>';
				}
            echo '</select>
		</td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo $base;
		echo '" id="baseColor" class="width100 float-left" name="base_color" />
		<div class="baseColorSelector colorSelector">
			<div class="baseBackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
			</div>
		</div>
        </td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo $eye;
		echo '" id="eyeColor" class="width100 float-left" name="eye_color" />
		<div class="eyeColorSelector colorSelector">
			<div class="eyeBackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
			</div>
		</div>
        </td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo $foot;
		echo '" id="feetearColor" class="width100 float-left" name="feet_ear_color" />
		<div class="feetearColorSelector colorSelector">
			<div class="feetearBackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
			</div>
		</div>
        </td>
	</tr>
	<tr>
    	<th class="content-subheader" colspan="3">Appearance Traits</th>
		<th class="content-subheader">Options</th>
	</tr>
	<tr>
    	<th class="content-subheader" colspan="1">Appearance Trait</th>
        <th class="content-subheader" colspan="2">Appearance Trait Color</th>
		<td rowspan="' . ($numTraits + 1) . '" class="vertical-top" id="buttons">
		<input id="add-trait" type="button" class="submit-input" value="Add another trait" /><br /><br />
		<input type="submit" class="submit-input" value="Generate Preview" name="preview" /><br /><br />';
		if($loggedin) {
			echo 'Design name:<br/> <input class="margin-top-small margin-bottom-small" type="text" name="design_name" size="20" maxlength="50" /><br />
			<input type="submit" class="submit-input margin-top-small" value="Save Design" name="save" /><br /><br />';
			$designs = Design::GetUserDesigns($userid);
			if(sizeof($designs) > 0) {
				echo 'Design:<br/> <select name="design_id" size="1">';
				foreach($designs as $des) {
					echo '<option value="' . $des->getID() . '"';
					if($design != NULL && $design->getID() == $des->getID()) { echo ' selected'; }
					echo '>' . $des->getName() . '</option>';
				}
				echo '</select><br /><input type="submit" class="submit-input margin-top-small" value="Overwrite Design" name="overwrite" />';
			}
		}
		
		echo '</td>
	</tr>';
	for($i = 1; $i <= $numTraits; $i++) {
		$trait_c = "FFFFFF";
		if(isset($_POST['trait' . $i . '_color'])) { $trait_c = $_POST['trait' . $i . '_color']; }
		elseif($design != NULL && $design->getNumTraits() > 0) { $trait_c = $design->getTraitColor($i - 1); }
	
		echo '<tr id="traitRow' . $i . '">
			<td align="center" class="vertical-top" colspan="1">';
				trait_dropdown("trait" . $i, $traitlist, $design, $i - 1);
			echo '</td>
			<td colspan="2" class="text-center vertical-top">
				<input type="text" value="';
				echo $trait_c;
				echo '" id="trait' . $i . 'Color" class="width100 float-left" name="trait' . $i . '_color" />
				<div class="trait' . $i . 'ColorSelector colorSelector">
					<div class="trait' . $i . 'BackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
					</div>
				</div>';
				echo '<a href="#"><img src="./images/icons/arrow_up.png" alt="^" class="moveRowUp';
				echo '" rowId="' . $i . '" /></a>';
				echo '<a href="#"><img src="./images/icons/arrow_down.png" alt="v" class="moveRowDown';
				echo '" rowId="' . $i . '" /></a>';
				echo '<a href="#"><img src="./images/icons/cross.png" alt="X" class="removeTraitRow';
				echo '" rowId="' . $i . '" /></a>';
			echo '</td>
		</tr>';
	}
	echo '</table></form><br />';

function trait_dropdown($fieldname, $optionlist, $design = NULL, $index = 0){
	$html = "none";
	if(isset($_POST[$fieldname])) { $html = $_POST[$fieldname]; }
	elseif($design != NULL && $design->getNumTraits() > 0) { $html = $design->getTraitName($index); }
  echo "<select class='width100 traitDropdown' name='$fieldname' size='1'>" . str_replace('value="' . $html . '"','value="' . $html . '" selected', $optionlist) . "</select>";
};

function trait_options($con) {
	$list = '<option value="none">-- Choose --</option>';
	$queryString = "SELECT * FROM `appearance_traits` WHERE `design_activated` = 'true' ORDER BY `trait_title` ASC;";
	$query = runDBQuery($queryString);
	while($trait = @mysql_fetch_assoc($query)) {
		$list .= '<option value="';
		if($trait['trait_type'] == 2) { $list .= 'mut'; }
		$list .= $trait['trait_name'] . '">' . $trait['trait_title'] . '</option>';
	}
	return $list;
};

function generateImage($design = NULL) {
	$url = "";
		
	if(isset($_POST['species'])) { $url .= "&species=" . $_POST['species']; }
	elseif($design != NULL) { $url .= "&species=" . strtolower($design->getSpeciesName()); }
	
	if(isset($_POST['base_color'])) { $url .= "&baseColor=" . $_POST['base_color']; }
	elseif($design != NULL) { $url .= "&baseColor=" . $design->getBase(); }
	
	if(isset($_POST['eye_color'])) { $url .= "&eyeColor=" . $_POST['eye_color']; }
	elseif($design != NULL) { $url .= "&eyeColor=" . $design->getEye(); }
	
	if(isset($_POST['feet_ear_color'])) { $url .= "&feetEarColor=" . $_POST['feet_ear_color']; }
	elseif($design != NULL) { $url .= "&feetEarColor=" . $design->getFoot(); }
	
	$numTraits = 0;
	if(isset($_POST['numTraits'])) { $numTraits = $_POST['numTraits']; }
	elseif($design != NULL) { $numTraits = $design->getNumTraits(); }
	if($numTraits > 0) { $url .= "&numTraits=$numTraits"; }
	
	for($i = 1; $i <= $numTraits; $i++) {
		$trait = "trait$i";
		$trait_color = $trait . '_color';
		$trait_color_text = $trait . '_color_text';
		if(isset($_POST["$trait"]) && isset($_POST["$trait_color"])) { 
			$url .= "&$trait=" . $_POST["$trait"] . "&$trait" . "Color=" . $_POST["$trait_color"]; 
		} elseif($design != NULL) {
			$trait_n = $design->getTraitName($i - 1);
			$trait_c = $design->getTraitColor($i - 1);
			$url .= "&$trait=" . $trait_n . "&$trait" . "Color=" . $trait_c; 
		}
	}
	
	if($url != "") { $url = '?' . substr($url, 1); }
	return "<img alt='Preview Design' src='./scripts/generate_design.php$url' />";
};

function displayColors() {
	$colors = array(
		"pink" => "FFBBBB",
		"red" => "BB0000", 
		"orange" => "FF8800",
		"yellow" => "FFFF00", 
		"green" => "009900", 
		"teal" => "33BBBB",
		"darkblue" => "000080",
		"blue" => "0000BB", 
		"lightblue" => "8888ff",
		"purple" => "DD99FF",
		"gray" => "999999", 
		"brown" => "886633",
		"black" => "000000",
		"white" => "FFFFFF"
	);
	$fonts = array(
		"pink" => "000000",
		"red" => "FFFFFF", 
		"orange" => "000000",
		"yellow" => "000000", 
		"green" => "000000", 
		"teal" => "000000",
		"darkblue" => "FFFFFF",
		"blue" => "FFFFFF", 
		"lightblue" => "000000",
		"purple" => "000000",
		"gray" => "000000", 
		"brown" => "000000",
		"black" => "FFFFFF",
		"white" => "000000"
	);
	$size = sizeof($colors);
	
	echo "<div class='listHolder hidden' id='listHolder'>";
	foreach($colors as $key => $value) {
		echo '<div class="colorList" style="background-color: #' . $value . '; color: #' . $fonts[$key] . ';">&nbsp;' . $key . '</div>';
	};
	echo '</div>';
};

include('./includes/footer.php');
?>