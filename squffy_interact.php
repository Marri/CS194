<table class="width100p">
<tr><th colspan="2" class="content-subheader">Interact with <?php echo $squffy->getName(); ?></th></tr>

<?php 
//Allow user to purchase hire rights if available
if(!$squffy->canWorkFor($userid) && $squffy->isHireable()) { ?>
    <tr>
    <th class="content-subheader width150">Hire</th>
    <td class="text-left"><input type="submit" class="submit-input" value="Purchase right to hire" /></td> </tr>
    <tr><td colspan="2" class="text-left"><span class="small">Squffy must be assigned to a job within 30 minutes.</span><br /><br /></td></tr>
<?php 
} 

//Allow user to purchase breeding rights if available
if($squffy->getOwnerID() != $userid) { ?>
    <tr>
    <th class="content-subheader width150">breed to</th>
    <td class="text-left"><input type="submit" class="submit-input" value="Purchase right to breed" /></td> </tr>
    <tr><td colspan="2" class="text-left"><span class="small">Squffy must be bred within 30 minutes.</span><br /><br /></td></tr>
<?php 
} 

//Allow user to request as mate, if available
if(!$squffy->hasMate()) { 
	$query = "SELECT * FROM squffies WHERE squffy_owner = $userid";
	$squffies = Squffy::getSquffies($query);
	$options = "";
	foreach($squffies as $mate) {
		if(!$squffy->canMateTo($mate)) { continue; }
		$options .= '<option value="">' . $mate->getName() . '</option>';
	}

	if(strlen($options) > 0) {
		?>
		<tr>
		<th class="content-subheader width150">Mate to</th>
		<td class="text-left">
		<select size="1">
		<?php echo $options; ?>
		</select>
		<input type="submit" class="submit-input" value="<?php echo ($squffy->getOwnerID() == $userid ? 'Set as mate' : 'Request as mate'); ?>" /></td>
		</tr>
<?php 
	} 
} else { 
$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR hire_rights = $userid";
$squffies = Squffy::getSquffies($query);
$worker_options = "";
foreach($squffies as $worker) {
	if(!$worker->canWorkFor($userid)) { continue; }
	if(!$worker->isAbleToWork()) { continue; }
	if($worker->getID() == $id) { continue; }
	$worker_options .= '<option value="">' . $worker->getName() . '</option>';
}

if(strlen($worker_options) > 0 && $squffy->isSick()) {
?>
<tr>
<th class="content-subheader width150">Heal</th>
<td class="text-left pad-left-small">
Doctor: <select size="1">
<?php echo $worker_options; ?>
</select>
<input type="submit" class="submit-input" value="Heal" /></td>
</tr>
<?php } } ?>

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

include('./includes/footer.php');
?>