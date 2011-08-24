<?php
$item_id = $_POST['use_item'];
$item = Item::getItemByID($item_id);

$designs = Design::GetUserDesigns($userid);
$num = sizeof($designs);
if($num < 1) {
	$errors[] = 'You do not have any designs saved! Create designs in the <a href="design.php">custom designer</a>.';
} elseif($item == NULL) {
	$errors[] = 'You tried to use an item that does not exist.';
} elseif(!$item->canMakeCustom()) {
	$errors[] = 'You tried to use an item that is not a custom seed.';
} elseif($inventory[$item->getColumnName()] < 1) {
	$errors[] = 'You tried to use an item that you do not own.';
} else {
	$item_info = Item::CustomInfo($item->getColumnName());
	$pay_type = $item_id;
	
	include('./create_custom_basic.php');
	die();
}
?>