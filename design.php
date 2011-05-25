<?php
$selected = "squffies";
$js[] = "design";
$js[] = "colorpicker";
$css[] = "design";
$css[] = "colorpicker";
include("./includes/header.php");

if(isset($_POST['save'])) {
	$save_valid = true;
	include('./save_design.php');
}

displayColors();

$traitlist = trait_options($con);
$numTraits = (isset($_POST['numTraits']) ? $_POST['numTraits'] : 1);
if($numTraits < 1) { $numTraits = 1; }

displayNotices($notices);
displayErrors($errors);
?>
<form action="./design.php" method="post" name="design_form">
<table cellspacing="4" class="content-table" id="contentTable">
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
            <?php echo generateImage(); ?>			
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
				while($species = @mysql_fetch_assoc($query)) {
					echo '<option value="' . strtolower($species['species_name']) . '"';
					if(isset($_POST['species']) && $_POST['species']== strtolower($species['species_name'])) { echo ' selected'; }
					echo '>' . $species['species_name'] . '</option>';
				}
            echo '</select>
		</td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo (isset($_POST['base_color']) ? $_POST['base_color'] : "F0DDA3");
		echo '" id="baseColor" class="width100 float-left" name="base_color" />
		<div class="baseColorSelector colorSelector">
			<div class="baseBackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
			</div>
		</div>
        </td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo (isset($_POST['eye_color']) ? $_POST['eye_color'] : "green");
		echo '" id="eyeColor" class="width100 float-left" name="eye_color" />
		<div class="eyeColorSelector colorSelector">
			<div class="eyeBackgroundSelector backgroundSelector" style="background-color: rgb(255, 255, 255);">
			</div>
		</div>
        </td>
        <td align="center" class="width150">
		<input type="text" value="';
		echo (isset($_POST['feet_ear_color']) ? $_POST['feet_ear_color'] : "brown");
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
			$query = "SELECT * FROM designs WHERE user_id = $userid";
			$result = runDBQuery($query);
			if(@mysql_num_rows($result) > 0) {
				echo 'Design:<br/> <select name="design" size="1">';
				while($info = @mysql_fetch_assoc($result)) {
					echo '<option value="' . $info['design_id'] . '">' . $info['design_name'] . '</option>';
				}
				echo '</select><br /><input type="submit" class="submit-input margin-top-small" value="Overwrite Design" name="overwrite" />';
			}
		}
		
		echo '</td>
	</tr>';
	for($i = 1; $i <= $numTraits; $i++) {
		echo '<tr id="traitRow' . $i . '">
			<td align="center" class="vertical-top" colspan="1">';
				trait_dropdown("trait" . $i, $traitlist);
			echo '</td>
			<td colspan="2" class="text-center vertical-top">
				<input type="text" value="';
				echo (isset($_POST['trait' . $i . '_color']) ? $_POST['trait' . $i . '_color'] : "FFFFFF");
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
	echo '</table></form>';

function trait_dropdown($fieldname, $optionlist){
  $html = (isset($_POST["$fieldname"]) ? $_POST["$fieldname"] : "none");
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

function generateImage() {
	$url = "";
		
	if(isset($_POST['species'])) { $url .= "&species=" . $_POST['species']; }
	if(isset($_POST['base_color_text'])) { $url .= "&baseColor=" . $_POST['base_color_text']; }
	elseif(isset($_POST['base_color'])) { $url .= "&baseColor=" . $_POST['base_color']; }
	if(isset($_POST['eye_color_text'])) { $url .= "&eyeColor=" . $_POST['eye_color_text']; }
	elseif(isset($_POST['eye_color'])) { $url .= "&eyeColor=" . $_POST['eye_color']; }
	if(isset($_POST['feet_ear_color_text'])) { $url .= "&feetEarColor=" . $_POST['feet_ear_color_text']; }
	elseif(isset($_POST['feet_ear_color'])) { $url .= "&feetEarColor=" . $_POST['feet_ear_color']; }
	
	$numTraits = (isset($_POST['numTraits']) ? $_POST['numTraits'] : 0);	
	if($numTraits > 0) { $url .= "&numTraits=" . $numTraits; }
	for($i = 1; $i <= $numTraits; $i++) {
		$trait = "trait$i";
		$trait_color = $trait . '_color';
		$trait_color_text = $trait . '_color_text';
		if(isset($_POST["$trait"])) { 
			if(isset($_POST["$trait_color_text"])) { 
				$url .= "&$trait=" . $_POST["$trait"] . "&$trait" . "Color=" . $_POST["$trait_color_text"]; 
			} elseif(isset($_POST["$trait_color"])) { 
				$url .= "&$trait=" . $_POST["$trait"] . "&$trait" . "Color=" . $_POST["$trait_color"]; 
			}
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