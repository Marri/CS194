<?php
$valid = true;
$name = $_POST['squffy_name'];
$gender = $_POST['gender'];
$design = $_POST['design'];
$payment = $_POST['pay_type'];
$isLocked = false;

if($gender != 'F' && $gender != 'M') {
	$valid = false;
	$errors[] = 'You did not pick a gender.';
}

if(!$name) {
	$valid = false;
	$errors[] = 'You did not pick a name.';
}

if(!$design) {
	$valid = false;
	$errors[] = 'You did not pick a design.';
} elseif (!Verify::VerifyID($design)) {
	$valid = false;
	$errors[] = 'You did not pick a valid design.';
} else {
	$design = Design::getDesignByID($design);
	if($design->getUser() != $userid) {
		$valid = false;
		$errors[] = 'That is not your design.';
	}
}

if($payment != 'tree' && $payment != 'ground' && !Verify::VerifyID($payment)) {
	$valid = false;
	$errors[] = 'You did not pay for this custom.';
} elseif ($payment == 'tree') {
	if(!$tree) {
		$valid = false;
		$errors[] = 'You have already made your free tree squffy.';
	}
	$item_info['species'] = 1;
	$item_info['num'] = 2;
	$isLocked = true;
} elseif ($payment == 'ground') {
	if(!$ground) {
		$valid = false;
		$errors[] = 'You have already made your free ground squffy.';
	}
	$item_info['species'] = 2;
	$item_info['num'] = 2;
	$isLocked = true;
} else {
	$item = Item::getItemByID($payment);
	if($item == NULL) {
		$valid = false;
		$errors[] = 'That item does not exist.';
	} elseif(!$item->canMakeCustom()) {
		$valid = false;
		$errors[] = 'You tried to use an item that is not a custom seed.';
	} elseif($inventory[$item->getColumnName()] < 1) {
		$valid = false;
		$errors[] = 'You tried to use an item that you do not own.';
	} else {
		$item_info = Item::CustomInfo($item->getColumnName());
	}
}

if($valid) {
	$numTraits = $design->getNumTraits();
	$species = $design->getSpecies();
	if($numTraits > $item_info['num']) {
		$valid = false;
		$errors[] = 'You tried to make a design with too many appearance traits.';
	}
	if($species != $item_info['species']) {
		$valid = false;
		$errors[] = 'You tried to make a design for the wrong species.';
	}
}

if($valid) {
	$id = Squffy::CreateCustom($name, $gender, $design, $userid, $isLocked);
	if(!$isLocked) { $user->updateInventory($item->getColumnName(), -1, true); }
	else { $user->useFreeSquffy($payment); }
	$notices[] = "Success! You have created your new custom. <a href='view_squffy.php?id=$id'>See $name's page here</a>.";
	displayNotices($notices);
	include('./includes/footer.php');
	die();
}
?>