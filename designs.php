<?php
$selected = "squffies";
$forLoggedIn = true;
include("./includes/header.php");

if(isset($_GET['delete_design'])) {
	$d_id = getID('delete_design');
	$design = Design::getDesignByID($d_id);
	if($design == NULL) {
		$errors[] = "This design does not exist.";
	} else {
		if($design->getUser() != $userid) {
			$errors[] = "This is not your design.";
		} else {
			$design->delete();
			$design = NULL;
		}
	}
}

displayNotices($notices);
displayErrors($errors);

echo '<table class="width100p" cellspacing="0">
<tr><th class="content-header" colspan="4">Your Saved Designs</th></tr>';

$designs = Design::GetUserDesigns($userid);
$i = 0;
foreach($designs as $design) {
	$numTraits = $design->getNumTraits();
	
	if($i % 4 == 0) { echo '<tr>'; }
	echo '<td class="text-center vertical-top width25p"><b>' . $design->getName() . '</b><br />
	<img src="' . $design->getThumbnail() . '" /><br />
	<a href="design.php?design=' . $design->getID() . '"><img src="./images/icons/pencil.png" alt="" /> Edit this design</a><br />
	<a href="designs.php?delete_design=' . $design->getID() . '"><img src="./images/icons/cross.png" alt="" /> Delete this design</a><br /><br />';
	echo '</td>';
	$i++;
	if($i % 4 == 0) { echo '</tr>'; }
}
if($i % 4 != 0) { 
	while($i % 4 != 0) { echo '<td></td>'; $i++; }
	echo '</tr>'; 
}

echo '</table>';

include('./includes/footer.php');
?>