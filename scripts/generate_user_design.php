<?php
include("../includes/connect.php");
include("../objects/appearance.php");
include("../objects/design.php");

$id = $_GET['design'];
$design = Design::GetDesign($id);
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

if(isset($_GET['thumbnail']) && $_GET['thumbnail'] == 'true') {
	$resize = true;
	$width = 200;
	$height = 175;
}

include('./generate_image.php');
?>