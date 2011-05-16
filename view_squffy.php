<?php
$selected = "squffies";
$css[] = "squffy";
include("./includes/header.php");

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

$title = $squffy->getName();
$links = array(
	array('name'=>'basics', 'url'=>"view_squffy.php?id=" . $squffy->getID()),
	array('name'=>'appearance', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=appearance'),
	array('name'=>'personality', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=personality'),
	array('name'=>'history', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=history'),
	array('name'=>'family', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=family'),
	array('name'=>'edit squffy', 'url'=>"edit_squffy.php?id=" . $squffy->getID()),
);
drawMenuTop($title, $links);
echo '<img src="' . $squffy->getURL() . '" alt="' . $squffy->getName() . '" />';

$view = 'home';
if(isset($_GET['view'])) { $view = $_GET['view']; }

if($view != 'home') { die(); }
?>
<table class="width100p">
<tr><th colspan="4" class="content-subheader">General information</th></tr>
<tr>
<th class="content-subheader width150">Name</th>
<td class="text-left pad-left-small width150"><?php echo $squffy->getName(); ?></td>
<th class="content-subheader width150">Gender</th>
<td class="text-left pad-left-small width150"><?php echo $squffy->getGenderDisplay(); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Age</th>
<td class="text-left pad-left-small"><?php echo $squffy->getAge(); ?> days old</td>
<th class="content-subheader width150">Species</th>
<td class="text-left pad-left-small"><?php echo $squffy->getSpecies(); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Degree</th>
<td class="text-left pad-left-small">
<?php 
if(!$squffy->isTaught() && !$squffy->isStudent()) { echo 'None'; }
else {echo $squffy->getDegreeType() . ' ' . $squffy->getDegreeName(); }
?>
</td>
<th class="content-subheader width150">Mate</th>
<td class="text-left pad-left-small width150">
<?php 
if($squffy->getMateID()) {
	$mate = Squffy::getSquffyByID($squffy->getMateID());
	echo $mate->getLink(); 
} else { echo 'None'; }
?></td>
</tr>
<tr>
<th class="content-subheader width150">Health</th>
<td colspan="3"><?php echo drawBar($squffy->getHealth()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Hunger</th>
<td colspan="3"><?php echo drawBar($squffy->getHunger(), false); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Energy</th>
<td colspan="3"><?php echo drawBar($squffy->getEnergy()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Happiness</th>
<td colspan="3"><?php echo drawBar($squffy->getHappiness()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Luck</th>
<td colspan="3"><?php echo drawBar($squffy->getLuck()); ?></td>
</tr>
<tr><th colspan="4" class="content-subheader">genetic information</th></tr>
<tr>
<th class="content-subheader width150">Strength</th>
<td colspan="3"><?php echo drawBar($squffy->getC1()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Speed</th>
<td colspan="3"><?php echo drawBar($squffy->getC2()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Agility</th>
<td colspan="3"><?php echo drawBar($squffy->getC3()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Endurance</th>
<td colspan="3"><?php echo drawBar($squffy->getC4()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Fertility</th>
<td colspan="3"><?php echo drawBar($squffy->getC5()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Trait Dominance</th>
<td colspan="3"><?php echo drawBar($squffy->getC6()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">Gene Dominance</th>
<td colspan="3"><?php echo drawBar($squffy->getC7()); ?></td>
</tr>
<tr>
<th class="content-subheader width150">XX Dominance</th>
<td colspan="3"><?php echo drawBar($squffy->getC8()); ?></td>
</tr>
</table>

</td>
</tr>
</table>

<?php
/*
echo '<h1>' . $squffy->getLink() . '</h1>';
$img = $squffy->getURL();
if(!file_exists($img)) { 
	$thumb = $squffy->getThumbnail();
	include('./scripts/reset_image.php');
}
echo "<img src='$img' alt='Squffy' /><br />";

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
</form>';*//*

//Debug
echo '<br><br><br>';
echo '<h1>Info</h1>';
echo 'id '. $squffy->getID() . '<br>';
echo 'health '. $squffy->getHealth() . '<br>';
if($squffy->getMateID()) {
echo 'mate ';
$mate = Squffy::getSquffyByID($squffy->getMateID());
echo $mate->getLink() . '<br>';
}
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

if(!$squffy->isCustom()) {
	echo '<br><br>family:<br>';
	$family = $squffy->getFamily();
	foreach($family as $relation => $rel_id) {
		if($rel_id == NULL) { continue; }
		$rel = Squffy::getSquffyByID($rel_id);
		echo $relation . ': ' . $rel->getLink() . '<br>';
	}
}*/

function drawBar($percent, $highIsGood = true) {
	$color = 'mid-percent';
	if($highIsGood) {
		if($percent > 66) { $color = "good-percent"; }
		if($percent < 33) { $color = "bad-percent"; }
	} else {
		if($percent > 66) { $color = "bad-percent"; }
		if($percent < 33) { $color = "good-percent"; }
	}
	
	return '<div class="percent-holder">
		<div class="percent-bar ' . $color . '" style="width: ' . $percent . '%;">
			' . $percent . '%
		</div>
	</div>';
}

include('./includes/footer.php');
?>