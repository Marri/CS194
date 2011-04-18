<?php
include("./includes/header.php");
include('./objects/squffy.php');

$id = $_GET['id'];
$squffy = Squffy::getSquffyByIDExtended($id, 
array(
Squffy::FETCH_FAMILY, 
Squffy::FETCH_FULL_APPEARANCE, 
Squffy::FETCH_PERSONALITY, 
Squffy::FETCH_SPECIES, 
Squffy::FETCH_ITEMS, 
Squffy::FETCH_DEGREE));

echo '<h1>' . $squffy->getName() . '</h1>';
echo 'id '. $squffy->getID() . '<br>';
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