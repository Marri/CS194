<?php
include("../includes/connect.php");
header("Content-type: image/png");

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
	"grey" => "999999", 
	"brown" => "886633",
	"black" => "000000",
	"white" => "FFFFFF"
);
$img_dir = "../images/generate/";

//Get species info
$species = isset($_GET['species']) ? $_GET['species'] : 'tree';
$queryString = "SELECT species_name FROM `species` WHERE `design_activated` = 'true';";
$query = runDBQuery($queryString);
$valid_species = false;
while($breed = @mysql_fetch_assoc($query)) {
	if(strtolower($breed['species_name']) == $species) { $valid_species = true; }
}
if(!$valid_species) { $species = 'tree'; }

//Create base
$design = addToBase("", $colors, $img_dir, $species, "base", "base", true, true, "F0DDA3");

//Get traits
$numTraits = (isset($_GET['numTraits']) ? $_GET['numTraits'] : 0);
$lower_traits = array();
$higher_traits = array();
for($i = 1; $i <= $numTraits; $i++) {
	$traitkey = 'trait' . $i;
	$traitname = $_GET["$traitkey"];
	$check=substr($traitname,0,3);
	if($check == "mut") { 
		$traitcolorkey = $traitkey . 'Color';
		$traitcolor = str_replace(" ","",strtolower($_GET["$traitcolorkey"]));
		if(isset($colors["$traitcolor"])) { $traitcolor = $colors["$traitcolor"]; }
		if(!isValidHex($traitcolor)) { $traitcolor = "FFFFFF"; }
		$traitname = substr($traitname, 3);
		$info['name'] = $traitname;
		$info['color'] = $traitcolor;
		$higher_traits[] = $info;
	} else {
		$lower_traits[] = $traitkey;
	}
}

$numLower = sizeof($lower_traits);
for($i = $numLower - 1; $i >= 0; $i--) {
	$traitkey = $lower_traits["$i"];
	$design = addToBase($design, $colors, $img_dir, $species, $_GET["$traitkey"], $traitkey, false, true);
}

//Add rest of markings
$design = addToBase($design, $colors, $img_dir, $species, "feetear", "feetEar", false, true, "brown");
$design = addToBase($design, $colors, $img_dir, $species, "eye", "eye", false, true, "green");
$design = addToBase($design, $colors, $img_dir, $species, "standard", "lines", false, false);

$image_width = imagesx($design);
$image_height = imagesy($design);

//Put remaining traits on base image
$numHigher = sizeof($higher_traits);
for($i = $numHigher - 1; $i >= 0; $i--) {
	$name = $higher_traits["$i"]['name'];
	$rgb = html2rgb($higher_traits["$i"]['color']);
	$base_location = $img_dir . $species . "/" . $species . "adult" . $name . "c.png";
	$highlight_location = $img_dir . $species . "/" . $species . "adult" . $name . "h.png";
	$lines_location = $img_dir . $species . "/" . $species . "adult" . $name . "l.png";
	$base_img = imagecreatefrompng($base_location);
	imagesavealpha($base_img, true);
	$truecolor_traitbase = imagecreatetruecolor($image_width, $image_height);
	$truecolor_traitbase = $base_img;
	$line_img = imagecreatefrompng($lines_location);
	imagesavealpha($line_img, true);
	$truecolor_traitlines = imagecreatetruecolor($image_width, $image_height);
	$truecolor_traitlines = $line_img;
	imagefilter($truecolor_traitbase, IMG_FILTER_GRAYSCALE);
	imagefilter($truecolor_traitbase, IMG_FILTER_BRIGHTNESS, -100);
	imagefilter($truecolor_traitbase, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]);
	imagecopy($design, $truecolor_traitbase, 0, 0, 0, 0, $image_width, $image_height);
	imagecopy($design, $truecolor_traitlines, 0, 0, 0, 0, $image_width, $image_height);
}

function addToBase($base_image, &$colors, $img_dir, $species, $name, $getName, $isBase, $hasColor, $useColor = "") {
	$location = $img_dir . $species . "/" . $species . "adult" . strtolower($name) . ".png";
	$img = imagecreatefrompng($location);
	imagesavealpha($img, true);
	$image_width = imagesx($img);
	$image_height = imagesy($img);
	$truecolor = imagecreatetruecolor($image_width, $image_height);
	$truecolor = $img;
	if($hasColor) {
		$getkey = $getName . 'Color';
		if(!isset($_GET["$getkey"]) && $useColor != "") {
			$color = $useColor;
		} else {
			$color = str_replace(" ","",strtolower(isset($_GET["$getkey"]) ? $_GET["$getkey"] : 'FFFFFF'));
		}
		if(isset($colors["$color"])) { $color = $colors["$color"]; }
		if(!isValidHex($color)) { $color = "FFFFFF"; }
		$rgb = html2rgb($color);
		imagefilter($truecolor, IMG_FILTER_GRAYSCALE);
		imagefilter($truecolor, IMG_FILTER_BRIGHTNESS, -100);
		imagefilter($truecolor, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]);
	}
	if($isBase) { return $truecolor; }
	imagecopy($base_image, $truecolor, 0, 0, 0, 0, $image_width, $image_height);
	imagedestroy($truecolor);
	return $base_image;
};

//HTML to RGB converter
function html2rgb($color)
{
  list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
  $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
  return array($r, $g, $b);
};

function isValidHex($hex) {
	$len = strlen($hex);
	if($len != 6) { return false; }
	if(strlen(preg_replace('/[a-fA-F0-9]+/', "", $hex)) > 0) { return false; }
	return true;
};

//Show image
imagepng($design);
imagedestroy($design);

?>