<?php
$selected = 'world';
$forLoggedIn = true;
include("./includes/header.php");

$id = getID('id');
if($id < 1) {
	displayErrors(array("This farm does not exist."));
	die();
}

$farm = Farm::GetFarmByID($id);
if($farm == NULL) {
	displayErrors(array("This farm does not exist."));
	die();
}

if($farm->getOwner() != $userid) {
	displayErrors(array("This farm does not belong to you."));
	die();
}

if(isset($_POST['plow'])) {
	include('./scripts/plow_farm.php');
}

if(isset($_POST['fertilize'])) {
	include('./scripts/fertilize_farm.php');
}

if(isset($_POST['plant'])) {
	include('./scripts/plant_farm.php');
}

if(isset($_POST['water'])) {
	include('./scripts/water_farm.php');
}

if(isset($_POST['weed'])) {
	include('./scripts/weed_farm.php');
}

displayErrors($errors);
displayNotices($notices);

echo '<div class="content-header width100p"><b>' . $farm->getName() . '</b></div>';
echo '<div class="padding-10">';
if($farm->getNumWorkers() > 0) {
	$response = $farm->getWorkers();
	$workers = $response['squffies'];
	$chore = $response['chore'];
	$finished = $response['done'];
	if($chore == 'Fertilize') { $chore = 'fertiliz'; }
	
	echo 'The following squffies are currently ' . strtolower($chore) . 'ing on this plot:';
	foreach($workers as $squffy) { echo '<li>'.$squffy->getLink().'<br />'; }
	echo '<br />Their work will be done at '. date("g:i a \o\\n F j, Y", strtotime($response['done']));
} else {
	switch($farm->getState()) {
		case 'Empty': 
			include('./farm_empty.php');		
			break;
		case 'Plowed':
			include('./farm_plowed.php');	
			break;	
		case 'Planted':
			include('./farm_growing.php');	
			break;	
		default: echo $farm->getState(); break;
	}
}
echo '</div>';
include("./includes/footer.php");
?>
