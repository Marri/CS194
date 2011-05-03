<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/personality.php');
include('./objects/squffy.php');

$errors = array();
$notices = array();

$id = getID("id");
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
if($squffy == NULL) { $errors[] = "That squffy does not exist."; }

displayErrors($errors);
displayNotices($notices);

if($squffy == NULL) {
	include('./includes/footer.php');
	die();
}

echo '<select size="1" name="item">';
$query = "SELECT item_id, item_name FROM items";
$result = runDBQuery($query);
while($item = @mysql_fetch_assoc($query)) {
	echo '<option value="' . $item['item_id'] . '">' . $item['item_name'] . '</option>';
}
echo '</select>';

include('./includes/footer.php');
?>