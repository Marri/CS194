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

foreach($squffies as $squffy) {
echo $squffy->getName() . "<br>";
}

include('./includes/footer.php');
?>