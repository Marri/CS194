<?php
include("../includes/connect.php");
include("../objects/appearance.php");
include("../objects/design.php");

$id = $_GET['design'];
$design = Design::getDesignByID($id);
$design->fetchSpecies();
$species = $design->getSpeciesName();
$base = $design->getBase();
$eye = $design->getEye();
$foot = $design->getFoot();

$markings = array();
$mutations = array();
$design->fetchTraits();
foreach($design->getTraits() as $trait) {
	if($trait['type'] == 1) { $markings[] = $trait; }
	else { $mutations[] = $trait; }
}

$filename = '../images/designs/' . floor($id / 1000) . '/' . $id . '.png';
if(isset($_GET['thumbnail']) && $_GET['thumbnail'] == 'true') {
	$filename = '../images/designs/' . floor($id / 1000) . '/t' . $id . '.png';
	$resize = true;
	$width = 200;
	$height = 175;
}

include_once('./generate_image.php');
$design = makeImage($species, $markings, $mutations, $base, $eye, $foot, false, true, $width, $height);
header("Content-type: image/png");
imagepng($design, $filename, 0);
imagepng($design);
imagedestroy($design);
?>