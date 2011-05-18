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
		//Squffy::FETCH_ITEMS, 
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
	array('name'=>'interact', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=interact'),
);
if($squffy->getOwnerID() == $userid) { $links[] = array('name'=>'edit squffy', 'url'=>"edit_squffy.php?id=" . $squffy->getID()); }
drawMenuTop($title, $links);

$view = 'home';
if(isset($_GET['view'])) { $view = $_GET['view']; }

if($view != 'family') { echo '<img src="' . $squffy->getURL() . '" alt="' . $squffy->getName() . '" />'; }

if($view == 'appearance') {
	include('./squffy_appearance.php');
	die();
}
if($view == 'family') {
	include('./squffy_family.php');
	die();
}
if($view == 'personality') {
	include('./squffy_personality.php');
	die();
}
if($view == 'history') {
	include('./squffy_history.php');
	die();
}
if($view == 'interact') {
	include('./squffy_interact.php');
	die();
}

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
<th class="content-subheader width150">Heritability</th>
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