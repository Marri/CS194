<?php
$selected = 'squffies';
$css[] = 'squffy';
include("./includes/header.php");

$id = getID("id", $userid);
if(!Verify::VerifyID($id)) {
	echo '<div class="errors">You must be logged in to view your drey.</div>';
	include('./includes/footer.php');
	die();
}

$owner = User::getUserByID($id);
if($owner == NULL) {
	echo '<div class="errors">That user does not exist.</div>';
	include('./includes/footer.php');
	die();
}

$query = "SELECT * FROM `squffies` WHERE `squffy_owner` = $id";

$filter = "";
if(isset($_GET['filter'])) { $filter = $_GET['filter']; }
if($filter == "hungry") { $query .= ' AND hunger > ' . Squffy::HUNGRY; }
if($filter == "sick") { $query .= ' AND health < ' . Squffy::SICK; }
if($filter == "working") { $query .= ' AND is_working = "true"'; }
if($filter == "pregnant") { $query .= ' AND is_pregnant = "true"'; }
if($filter == "market") { $query .= ' AND is_in_market = "true"'; }
if($filter == "breedable") { $query .= ' AND is_breedable = "true"'; }
if($filter == "hireable") { $query .= ' AND is_hireable = "true"'; }

$sort = "";
if(isset($_GET['sort'])) { $sort = $_GET['sort']; }
if($sort == "name") { $query .= ' ORDER BY squffy_name ASC'; }
elseif($sort == "species") { $query .= ' ORDER BY squffy_species ASC'; }
elseif($sort == "age") { $query .= ' ORDER BY (TO_DAYS(squffy_birthday) - age_offset) - CASE WHEN is_custom = "true" THEN 20 ELSE 0 END ASC'; }
elseif($sort == "gender") { $query .= ' ORDER BY squffy_gender DESC'; }

$squffies = Squffy::getSquffies($query);

$i = 0;
echo '<table class="width100p" cellspacing="0"><tr><th colspan="4" class="content-header">';
if($id == $userid) { echo 'Your Squffies'; }
else { echo possessive($owner->getUsername()) . ' squffies'; }
echo '</th></tr><form action="drey.php" method="get">
<tr><td class="width33p">&nbsp;Sort by: <select size="1" name="sort">
<option value="name">Name</option>
<option value="age"';
if($sort == "age") { echo ' selected'; }
echo '>Age</option>
<option value="gender"';
if($sort == "gender") { echo ' selected'; }
echo '>Gender</option>
<option value="species"';
if($sort == "species") { echo ' selected'; }
echo '>Species</option></select></td>
<td colspan="2" class="text-center">
<a href="profile.php?id=' . $owner->getID() . '">Go to owner\'s profile</a>
</td><td class="text-right width33p">
Filter by: 
<select size="1" name="filter">
<option value="all">View all</option>
<option value="hungry"';
if($filter == "hungry") { echo ' selected'; }
echo '>Hungry</option>
<option value="sick"';
if($filter == "sick") { echo ' selected'; }
echo '>Sick</option>
<option value="working"';
if($filter == "working") { echo ' selected'; }
echo '>Working</option>
<option value="pregnant"';
if($filter == "pregnant") { echo ' selected'; }
echo '>Pregnant</option>
<option value="market"';
if($filter == "market") { echo ' selected'; }
echo '>Available in the market</option>
<option value="breedable"';
if($filter == "breedable") { echo ' selected'; }
echo '>Available for breeding</option>
<option value="hireable"';
if($filter == "hireable") { echo ' selected'; }
echo '>Available for hire</option>
</select>
<input type="submit" value="Go" class="submit-input" /></form></td></tr>
<tr><td colspan="4"><table class="width100p">
';
foreach($squffies as $squffy) {
	if($squffy->isEgg()) { continue; }
	if($i%4 == 0) { echo '<tr>'; }
	echo '<td class="vertical-top width150 bordered ';
	if($squffy->getGender() == 'F') { echo 'fe'; }
	echo 'male text-center"><div class="float-left width100">
	<a href="view_squffy.php?id=' . $squffy->getID() .'">
	<img src="' . $squffy->getThumbnail() . '" /><br>';
	echo $squffy->getName() . '</a></div>
	<div class="info-box">';	
	if($squffy->isSick()) { echo '<img src="./images/icons/sick.png" title="Sick" /> Sick<br />'; }
	if($squffy->isHungry()) { echo '<img src="./images/icons/hungry.png" title="Hungry" /> Hungry<br />'; }
	if($squffy->isSick() || $squffy->isHungry()) { echo '<br />'; }
	//if($squffy->isForSale()) { echo '<img src="./images/icons/sale.png" title="For sale" /> '; }
	if($squffy->isBreedable()) { echo '<img src="./images/icons/heart.png" title="For breeding" /> Breedable<br />'; }
	if($squffy->isPregnant()) { echo '<img src="./images/icons/egg.png" title="Pregnant" /> Pregnant<br />'; }
	if($squffy->isWorking()) { echo '<img src="./images/icons/clock.png" title="Working" /> Working<br />'; }
	echo '</div></td>';
	if($i%4 == 3) { echo '</tr>'; }
	$i++;
}
if($i%4 > 0) { 
	while($i%4 > 0) { echo '<td class="width150"></td>'; $i++; }
	echo '</tr>'; 
}
if(sizeof($squffies) < 1) { echo '<tr><td colspan="4" class="text-center italic">No squffies here!</td></tr>'; }
echo '</table></td></tr></table>';

include('./includes/footer.php');
?>