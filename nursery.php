<?php
$selected = 'squffies';
include("./includes/header.php");

$id = getID('id', $userid);
if(!Verify::VerifyID($id)) {
	echo '<div class="errors">You must be logged in to view your nursery.</div>';
	include('./includes/footer.php');
	die();
}

$owner = User::getUserByID($id);
if($owner == NULL) {
	echo '<div class="errors">That user does not exist.</div>';
	include('./includes/footer.php');
	die();
}

$query = "SELECT * FROM `squffies` WHERE `squffy_owner` = $id AND is_custom = 'false' AND TO_DAYS(now()) - TO_DAYS(squffy_birthday) < 6";
$squffies = Squffy::getSquffies($query);
?>
<table class="width100p" cellspacing="0">
<tr><th class="content-header"><?php
if($loggedin && $id == $userid) { echo 'Your'; }
else { echo possessive($owner->getUsername()); } ?>
 Nursery</th></tr>
<tr><th colspan="5"><a href="profile.php?id=<?php echo $id; ?>">Go to owner's profile</a></th></tr>
<tr><td>

<table class="width100p">
<?php
$i = 0;
foreach($squffies as $squffy) {
	if(!$squffy->isEgg()) { continue; }
	if($i%5 == 0) { echo '<tr>'; }
	echo '<td class="width150 bordered text-center"><img src="' . $squffy->getThumbnail() . '" /><br>';
	echo $squffy->getLink() . '<br />Hatches: ';
	$date = strtotime($squffy->getBirthday()) + 60 * 60 * 24 * 5;
	echo date("F j, Y",  $date);
	echo '</td>';
	if($i%5 == 4) { echo '</tr>'; }
	$i++;
}
if($i%5 > 0) { 
	while($i%5 > 0) { echo '<td class="width150"></td>'; $i++; }
	echo '</tr>'; 
}
if(sizeof($squffies) < 1) { echo '<tr><td colspan="4" class="text-center italic">No squffies here!</td></tr>'; }
?>
</table>

</td></tr></table>
<?php
include("./includes/footer.php");
?>
