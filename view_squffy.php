<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/squffy.php');

$id = $_GET['id'];
$squffy = Squffy::getSquffyByIDExtended
	($id, 
	array(
		Squffy::FETCH_FAMILY, 
		Squffy::FETCH_FULL_APPEARANCE, 
		Squffy::FETCH_PERSONALITY, 
		Squffy::FETCH_SPECIES, 
		Squffy::FETCH_ITEMS, 
		Squffy::FETCH_DEGREE
	)
);

$errors = array();
$notices = array();
include('./scripts/squffy_actions.php');
displayErrors($errors);
displayNotices($notices);

echo '<a href="http://127.0.0.1/view_squffy.php?id=112"><h1>' . $squffy->getName() . '</h1></a>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
ID: <input type="text" name="mate_id" length="10" />
<input type="submit" name="breed" value="Breed to" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
ID: <input type="text" name="mate_id" length="10" />
<input type="submit" name="set_mate" value="Set mate" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
ID: <input type="text" name="doctor_id" length="10" />
<input type="submit" name="heal" value="Get healed by" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="farming" value="Set as farmer" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="foresting" value="Set as forester" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="teaching" value="Set as teacher" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="nursemaiding" value="Set as nursemaid" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="doctoring" value="Set as doctor" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="midwifeing" value="Set as midwife" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="cooking" value="Set as cook" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="baking" value="Set as baker" />
</form>';
echo '<form action="view_squffy.php?id=' . $id . '" method="post">
<input type="submit" name="building" value="Set as builder" />
</form>';

//Debug
echo '<br><br>';
echo '<br><br>';
echo 'id '. $squffy->getID() . '<br>';
echo 'health '. $squffy->getHealth() . '<br>';
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