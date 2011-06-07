<?php
if(!isset($squffy)) { die(); }

//Update information
if(isset($_POST['update_squffy'])) {
	include('./scripts/squffy_update.php'); 
}

//Set mate
if(isset($_POST['set_mate'])) { 
	$mate_id = getID('mate_id');
	$mate = Squffy::getSquffyByID($mate_id);
	include('./scripts/squffy_mate.php'); 
}

//Breed
if(isset($_POST['breed'])) {
	include('./scripts/squffy_breed.php'); 
}

//Buy breed rights
if(isset($_POST['buy_breed'])) {
	include('./scripts/squffy_breed_rights.php');
}

//Buy hire rights
if(isset($_POST['buy_hire'])) {
	include('./scripts/squffy_hire_rights.php');
}

//Learn from teacher
if(isset($_POST['taught'])) {
	$days = 4;
	include('./scripts/squffy_learn.php'); 
}

//Reorder appearance traits
if(isset($_POST['reorder'])) {
	include('./scripts/squffy_reorder.php'); 
	include('./scripts/reset_image.php'); 
}

//Reset image
if(isset($_POST['reset_image'])) {
	include('./scripts/reset_image.php');
	$notices[] = "Your squffy's image has been reset. You may need to press Ctrl+F5 to see the changes."; 
}

//Heal
if(isset($_POST['heal'])) {
	$original = $squffy->getHealth();
	if($original > 99) {
		$errors[] = $squffy->getName() . " does not need healing right now.";
	} else {
		$doctor_id = $_POST['doctor_id'];
		$doctor = Squffy::getSquffyByID($doctor_id);
		if($doctor->getEnergy() < 5) { 
			$errors[] = $doctor->getName() . " must have at least 5 energy to heal.";
		} else {
			$squffy->heal($doctor);
			$change = $squffy->getHealth() - $original;
			$notices[] = possessive($squffy->getName()) . " health has increased by $change.";
		}
	}
}

//Feed
if(isset($_POST['feed'])) {
	$original = $squffy->getHunger();
	if($original < 1) {
		$errors[] = $squffy->getName() . " does not need feeding right now.";
	} else {
		$food = $_POST['food_id'];
		$item = Item::getItemByID($food);
		$inventory = $user->getInventory();
		$col = $item->getColumnName();
		
		if($inventory[$col] < 1) {
			$errors[] = 'You do not have enough ' . strtolower($item->getName()) . 's.';
		} else {
			$old = $squffy->getHunger();
			$squffy->feed($item);
			$user->updateInventory($col, -1, true);
			$change = $old - $squffy->getHunger();
			$notices[] = possessive($squffy->getName()) . ' hunger has decreased by ' . $change . '.';
		}
	}
}

//Undress
if(isset($_POST['remove_item'])) {
	$squffy->fetchItems();
	$items = $squffy->getItems();
	$iid = getID('remove_item');
	$item = Item::getItemByID($iid);
	
	$wearing = -1;
	for($i = 0; $i < sizeof($items); $i++) {
		$tmp = $items[$i];
		if($tmp->getID() == $iid) {  $wearing = $i; }
	}
	
	if($item == NULL) {
		$errors[] = "You must pick an item to remove.";
	} else {
		if($wearing < 0) {
			$errors[] = "Your squffy is not wearing this item.";
		} else {
			$squffy->removeItem($item, $wearing);
			$user->updateInventory($item->getColumnName(), 1, true);
			include('./scripts/reset_image.php');
		}
	}
}

//Dress
if(isset($_POST['dress'])) {
	$iid = getID('outfit_id');
	$item = Item::getItemByID($iid);
	$inventory = $user->getInventory();
	$squffy->fetchItems();
	$items = $squffy->getItems();
	
	if($item == NULL) {
		$errors[] = "You must pick an item to put on.";
	} else {
		$name = $item->getName();
		$col = $item->getColumnName();
		if($inventory[$col] < 1) {
			$errors[] = 'You do not have any ' . pluralize($name) .' to put on.';
		} else {
			$hasB = false;
			$sameItem = false;
			foreach($items as $i) { 
				if($i->isBackground()) { $hasB = true; } 
				if($i->getID() == $iid) { $sameItem = true; }
			}
			if($hasB && $item->isBackground()) {
				$errors[] = 'You already have a background on this squffy.';
			} elseif($sameItem) {
				$errors[] = 'You already have this item on this squffy.';
			} else {
				$user->updateInventory($col, -1, true);
				$squffy->addItem($item);
				include('./scripts/reset_image.php');
			}
		}
	}	
}

//Accept request
if(isset($_POST['accept-mate'])) {
	$val = $_POST['accept-mate'];
	$name = substr($val, 7);
	$id = getID($name);
	$mate = Squffy::getSquffyByID($id);
	$acceptValidRequest = false;
	$requests = MatingNotification::getRequests($id, $userid);
	if(sizeof($requests) > 0) {
		foreach($requests as $request) {
			if($request->getSentSquffy() == $id) { $acceptValidRequest = true; }
		}
	}
	
	if(!$acceptValidReqest) {
		$errors[] = "This request does not seem to exist.";
	} else {
		include('./scripts/squffy_mate.php');
		MatingNotification::deleteNotification($squffy, $mate);	
	}
}

if(isset($_POST['reject-mate'])) {
	$val = $_POST['accept-mate'];
	$name = substr($val, 7);
	$id = getID($name);
	$mate = Squffy::getSquffyByID($id);
	MatingNotification::deleteNotification($squffy, $mate);	
}
?>