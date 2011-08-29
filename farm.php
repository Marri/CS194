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

if(isset($_POST['harvest'])) {
	$food = $farm->getFoodID();
	$food = Item::getItemByID($food);
	$name = $food->getName();
	$name = substr($name, 7);
	$name = substr($name, 0, strlen($name) - 6);
	$grown = $food->getItemFromName($name);
	$crops = $farm->getNumCrops();
	$amount = $crops * mt_rand(5,15);
	if($farm->isFertilized()) { $amount = $crops * mt_rand(8, 20); }
	$user->updateInventory($grown->getColumnName(), $amount, true);
	$query = "UPDATE farms SET date_ripe = NULL, dryness = NULL, weeds=NULL, cur_state = 'Empty', food_id = NULL, num_crops = NULL WHERE farm_id = $id";
	runDBQuery($query);
	displayNotices(array("Success! You have harvested $amount ".pluralize($name)."."));
	include('./includes/footer.php');
	die();
}

if(isset($_POST['clear'])) {
	$query = "UPDATE farms SET date_ripe = NULL, cur_state = 'Empty', food_id = NULL, num_crops = NULL WHERE farm_id = $id";
	runDBQuery($query);
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
		case 'Grown':
			echo 'Your farm is ready to harvest!<br /><br /><form action="farm.php?id='.$id.'" method="post">
			<input type="submit" name="harvest" value="Collect crops" class="margin-top-small submit-input" />
			</form>';
			break;
		case 'Dead':
			echo 'Your crops have died!<br /><br /><form action="farm.php?id='.$id.'" method="post">
			<input type="submit" name="clear" value="Clear dead crops" class="margin-top-small submit-input" />
			</form>';
			break;
		default: echo $farm->getState(); break;
	}
}
echo '</div>';
include("./includes/footer.php");
?>
