<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/squffy.php');

$id = getID("id", $userid);
if($id < 1) {
	echo '<div class="errors">You must be logged in to view your drey.</div>';
	include('./includes/footer.php');
	die();
}

$query = "SELECT * FROM `squffies` WHERE `squffy_owner` = $id";
$squffies = Squffy::getSquffies($query);

$i = 0;
echo '<table class="width100p"><tr><th colspan="5" class="content-header">Your Squffies</th></tr>';
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