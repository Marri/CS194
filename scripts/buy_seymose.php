<?php
$valid = true;

$buying = $_POST['buying_id'];
$item = Item::getItemByID($buying);
if($item == NULL) {
	$valid = false;
	$errors[] = 'That item does not exist.';
} else {
	$price = $item->getSeymosePrice();
	$col = $item->getSeymoseItem();
	$colItem = Item::getItemByID($col);
	if($user->getAmount($colItem->getColumnName()) < $price) {
		$valid = false;
		$errors[] = 'You cannot afford that item.';
	}
}

if($valid) {
	$user->updateInventory($colItem->getColumnName(), $price * -1, true);
	$user->updateInventory($item->getColumnName(), 1, true);
	$notices[] = 'Success! You have purchased a ' . $item->getName() . '.';
}
?>