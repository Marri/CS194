<?php
$valid = true;

$buying = $_POST['buying_id'];
$item = Item::getItemByID($buying);
if($item == NULL) {
	$valid = false;
	$errors[] = 'That item does not exist.';
} else {
	$price = $item->getOliviaPrice();
	if($user->getAmount('squffy_dollar') < $price) {
		$valid = false;
		$errors[] = 'You cannot afford that item.';
	}
}

if($valid) {
	$user->updateInventory('squffy_dollar', $price * -1, true);
	$user->updateInventory($item->getColumnName(), 1, true);
	$notices[] = 'Success! You have purchased a ' . $item->getName() . '.';
}
?>