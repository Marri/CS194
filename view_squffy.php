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
);

if($loggedin) {
	$links[] = array('name'=>'interact', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=interact');
	if($squffy->getOwnerID() == $userid || $user->isAdmin()) { 
		$links[] = array('name'=>'edit squffy', 'url'=>"edit_squffy.php?id=" . $squffy->getID()); 
	}
}

$cur = "odd";
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
if($view == 'interact' && $loggedin) {
	include('./squffy_interact.php');
	die();
}

if($view != 'home') { die(); }
?>
<table class="width100p squffy-table" cellspacing="0" cellpadding="0">
<tr><th colspan="4" class="content-subheader">General Information</th></tr>
<tr class="odd">
<th class="content-miniheader">Name</th>
<td class="text-left pad-left-small"><?php echo $squffy->getName(); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader">Gender</th>
<td class="text-left pad-left-small"><?php echo $squffy->getGenderDisplay(); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader">Age</th>
<td class="text-left pad-left-small"><?php
if(!$squffy->isEgg()) {
 echo $squffy->getAge() . ' days old';
} else { echo 'Egg'; }
?>
</td>
</tr>
<tr class="even">
<th class="content-miniheader">Birthday</th>
<td class="text-left pad-left-small"><?php 
$date = strtotime($squffy->getBirthday());
if(!$squffy->isCustom()) { $date += 60 * 60 * 24 * 5; }
echo date("F j, Y",  $date); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader">Species</th>
<td class="text-left pad-left-small"><?php echo $squffy->getSpecies(); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader">Degree</th>
<td class="text-left pad-left-small">
<?php 
if(!$squffy->isTaught() && !$squffy->isStudent()) { echo 'None'; }
else {echo $squffy->getDegreeType() . ' ' . $squffy->getDegreeName(); }
?>
</td>
</tr>
<tr class="odd">
<th class="content-miniheader">Mate</th>
<td class="text-left pad-left-small">
<?php 
if($squffy->getMateID()) {
	$mate = Squffy::getSquffyByID($squffy->getMateID());
	echo $mate->getLink(); 
} else { echo 'None'; }
?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Health</th>
<td colspan="3"><?php echo drawBar($squffy->getHealth()); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader width150">Hunger</th>
<td colspan="3"><?php echo drawBar($squffy->getHunger(), false); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Energy</th>
<td colspan="3"><?php echo drawBar($squffy->getEnergy()); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader width150">Happiness</th>
<td colspan="3"><?php echo drawBar($squffy->getHappiness()); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Luck</th>
<td colspan="3"><?php echo drawBar($squffy->getLuck()); ?></td>
</tr>
<tr><th colspan="4" class="content-subheader">Genetic Information</th></tr>
<tr class="odd">
<th class="content-miniheader width150">Strength</th>
<td colspan="3"><?php echo drawBar($squffy->getC1()); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Speed</th>
<td colspan="3"><?php echo drawBar($squffy->getC2()); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader width150">Agility</th>
<td colspan="3"><?php echo drawBar($squffy->getC3()); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Endurance</th>
<td colspan="3"><?php echo drawBar($squffy->getC4()); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader width150">Fertility</th>
<td colspan="3"><?php echo drawBar($squffy->getC5()); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">Heritability</th>
<td colspan="3"><?php echo drawBar($squffy->getC6()); ?></td>
</tr>
<tr class="odd">
<th class="content-miniheader width150">Gene Dominance</th>
<td colspan="3"><?php echo drawBar($squffy->getC7()); ?></td>
</tr>
<tr class="even">
<th class="content-miniheader width150">XX Dominance</th>
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