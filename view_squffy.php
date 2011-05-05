<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/personality.php');
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

include('./scripts/squffy_actions.php');
displayErrors($errors);
displayNotices($notices);

echo '<h1>' . $squffy->getLink() . '</h1>';
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
Teacher ID: <input type="text" name="teacher_id" length="10" />
Degree: <select size="1" name="degree_id">';
$query = "SELECT * FROM degrees";
$result = runDBQuery($query);
while($d = mysql_fetch_assoc($result)) {
	echo '<option value="' . $d['degree_id'] . '">' . $d['degree_name'] . '</option>';
}
echo '</select>
<input type="submit" name="taught" value="Start degree with teacher" /></form>';
/*
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
</form>';*/

//Debug
echo '<br><br><br>';
echo '<h1>Info</h1>';
echo 'id '. $squffy->getID() . '<br>';
echo 'health '. $squffy->getHealth() . '<br>';
echo 'mate ';
$mate = Squffy::getSquffyByID($squffy->getMateID());
echo $mate->getLink() . '<br>';
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