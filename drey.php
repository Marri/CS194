<?php
$selected = "squffies";
include("./includes/header.php");

$id = getID("id", $userid);
if($id < 1) {
	echo '<div class="errors">You must be logged in to view your drey.</div>';
	include('./includes/footer.php');
	die();
}

$query = "SELECT * FROM `squffies` WHERE `squffy_owner` = $id";
$filter = "all";
if(isset($_GET['filter'])) { $filter = $_GET['filter']; }
if($filter == "hungry") { $query .= ' AND hunger > ' . Squffy::HUNGRY; }
if($filter == "sick") { $query .= ' AND health < ' . Squffy::SICK; }
if($filter == "working") { $query .= ' AND is_working = "true"'; }
if($filter == "pregnant") { $query .= ' AND is_pregnant = "true"'; }
if($filter == "market") { $query .= ' AND is_in_market = "true"'; }
if($filter == "breedable") { $query .= ' AND is_breedable = "true"'; }
if($filter == "hireable") { $query .= ' AND is_hireable = "true"'; }

$squffies = Squffy::getSquffies($query);

$i = 0;
echo '<table class="width100p"><tr><th colspan="5" class="content-header">Your Squffies</th></tr>';
echo '<tr><td colspan="3">Sort by: <select size="1"><option>Name</option><option>Age</option><option>Gender</option><option>Species</option></select></td>
<td colspan="2" class="text-right"><form action="drey.php" method="get">Filter by: 
<select size="1" name="filter">
<option value="all">View all</option>
<option value="hungry">Hungry</option>
<option value="sick">Sick</option>
<option value="working">Working</option>
<option value="pregnant">Pregnant</option>
<option value="market">Available in the market</option>
<option value="breedable">Available for breeding</option>
<option value="hireable">Available for hire</option>
</select>
<input type="submit" value="Go" /></form></td></tr>';
foreach($squffies as $squffy) {
	if($i%5 == 0) { echo '<tr>'; }
	echo '<td class="width150 bordered ';
	if($squffy->getGender() == 'F') { echo 'fe'; }
	echo 'male text-center"><img src="' . $squffy->getThumbnail() . '" /><br>';
	echo $squffy->getLink() . '</td>';
	if($i%5 == 4) { echo '</tr>'; }
	$i++;
}
if($i%5 > 0) { 
while($i%5 > 0) { echo '<td class="width150"></td>'; $i++; }
echo '</tr>'; }
echo '</table>';

include('./includes/footer.php');
?>