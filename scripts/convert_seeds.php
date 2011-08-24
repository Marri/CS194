<?php
$valid = true;

$num = $_POST['num_convert'];
if(!is_numeric($num)) {
	$errors[] = 'You must specify how many items you wish to convert.';
	$valid = false;
} elseif($num < 1) {
	$errors[] = 'You must specify how many items you wish to convert.';
	$valid = false;
}	
	
$item_id = $_POST['food_convert'];
$item = Item::getItemByID($item_id);
if($item == NULL) {
	$valid = false;
	$errors[] = 'That item does not exist.';
} elseif($valid) {
	if($user->getAmount($item->getColumnName()) < $num) {
		$valid = false;
		$errors[] = 'You do not have that many items to convert.';
	}
}

if($valid) {
	$new_name = 'bag_of_' . $item->getColumnName() . '_seeds';
	$user->updateInventory($item->getColumnName(), $num * -1, true);
	$user->updateInventory($new_name, $num, true);
	$notices[] = 'Success! You have obtained seeds.';
}
?>