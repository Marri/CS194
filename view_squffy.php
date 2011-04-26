<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/squffy.php');

$errors = array();
$id = $_GET['id'];
$squffy = Squffy::getSquffyByIDExtended($id, 
array(
Squffy::FETCH_FAMILY, 
Squffy::FETCH_FULL_APPEARANCE, 
Squffy::FETCH_PERSONALITY, 
Squffy::FETCH_SPECIES, 
Squffy::FETCH_ITEMS, 
Squffy::FETCH_DEGREE));

if(isset($_POST['set_mate'])) {
	$valid = true;
	if($squffy->hasMate()) { 
		$errors[] = $squffy->getName() . " already has a mate."; 
		$valid = false;
	}
	$mate_id = $_POST['mate_id'];
	$mate = Squffy::getSquffyByID($mate_id);
	if($mate->hasMate()) { 
		$errors[] = $mate->getName() . " already has a mate.";
		$valid = false;
	}
	if($valid) {
		$mate->setMate($squffy);
		$squffy->setMate($mate);
	}
}

displayErrors($errors);

echo '<h1>' . $squffy->getName() . '</h1>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
ID: <input type="text" name="mate_id" length="10" />
<input type="submit" name="set_mate" value="Set mate" />
</form>';

//Debug
echo '<br><br>';
echo '<br><br>';
echo 'id '. $squffy->getID() . '<br>';
echo 'mate '. $squffy->getMateID() . '<br>';
echo 'age '. $squffy->getAge() . '<br>';
echo 'species id '. $squffy->getSpeciesID() . '<br>';
echo 'species '. $squffy->getSpecies() . '<br>';
echo 'bday '. date("m-d-y", strtotime($squffy->getBirthday())) . '<br>';
echo 'gender '.$squffy->getGender() .'<br>';
echo 'degree '.$squffy->getDegreeType() .' '.$squffy->getDegreeName() .'<br>';
echo 'pregnant '.$squffy->isPregnant() .'<br>';
echo 'working '.$squffy->isWorking() .'<br>';
echo 'breedable '.$squffy->isBreedable() .'<br>';
echo 'for sale '.$squffy->isInMarket() .'<br>';
echo 'hireable '.$squffy->isHireable() .'<br>';
echo 'custom '.$squffy->isCustom() .'<br><br>appearance:<br>';
print_r($squffy->getAppearanceTraits());
echo '<br><br>personality:<br>';
print_r($squffy->getPersonalityTraits());
echo '<br><br>items:<br>';
print_r($squffy->getItems());

include('./includes/footer.php');
?>