<table class="width100p squffy-table" cellspacing="0">
<tr><th colspan="2" class="content-subheader">Interact with <?php echo $squffy->getName(); ?></th></tr>
<form action="view_squffy.php?id=<?php echo $id; ?>&view=interact" method="post">

<?php 
if($squffy->getOwnerID() == $userid) { 
	$item_list = Item::getItemList();
	
	$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR hire_rights = $userid";
	$squffies = Squffy::getSquffies($query);
	$worker_options = "";
	foreach($squffies as $worker) {
		if(!$worker->canWorkFor($userid)) { continue; }
		if(!$worker->isAbleToWork()) { continue; }
		if($worker->getID() == $id) { continue; }
		$worker_options .= '<option value="' . $worker->getID() . '">' . $worker->getName() . '</option>';
	}
	
	//Feed them
	$inventory = $user->getInventory();
	$items = "";
	foreach($item_list as $item) { 
		if($item->isFood()) {
			if(isset($inventory[$item->getColumnName()]) && $inventory[$item->getColumnName()] > 0) {
				$items .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>';
			}
		}
	}
	
	echo '<tr';
	$cur = row($cur);
	echo '>
	<th class="content-miniheader width150">Feed</th>
	<td class="text-left">';
	
	if($squffy->getHunger() < 1) {
		echo '<span class="small-error">Your squffy is already full.</span>';
	} elseif(strlen($items) < 1) {
		echo '<span class="small-error">You have no available food.</span>';
	} else {
		echo 'Food: <select size="1" name="food_id">
		' . $items . '
		</select>
		<input type="submit" class="submit-input" name="feed" value="Feed" /></td>
		</tr>';
	}
	
	//If they are sick, allow healing.
	echo '<tr';
	$cur = row($cur);
	echo '>
	<th class="content-miniheader width150">Heal</th>
	<td class="text-left">';
	if($squffy->getHealth() >= 100) {
		echo '<span class="small-error">Your squffy is already healthy.</span>';
	} elseif(strlen($worker_options) < 1) {
		echo '<span class="small-error">You have no squffies available to act as doctor.</span>';
	} else {
		echo 'Doctor: <select size="1" name="doctor_id">
		' . $worker_options . '
		</select>
		<input type="submit" class="submit-input" name="heal" value="Heal" />';
	}    
	echo '</td></tr>';
	
	//Dress
	echo '<tr';
	$cur = row($cur);
	echo '><th class="content-miniheader width150">Dress</th>
	<td class="text-left">';
	$options = '';
	foreach($item_list as $item) { 
		if($item->isClothing() || $item->isBackground()) { $options .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>'; } 
	}
	if(strlen($options) > 0) {
		echo '<select name="outfit_id" size="1">' . $options . '</select> <input type="submit" class="submit-input" name="dress" value="Put on" />';
	} else {
		echo '<span class="small-error">You have no clothing or backgrounds available.</span>';
	}
	echo '</td></tr>';
	
	//If they can be taught, allow teaching.
	echo '<tr';
	$cur = row($cur);
	echo '>
	<th class="content-miniheader width150">Learn</th>
	<td class="text-left">';
	if($squffy->isStudent()) {
		echo '<span class="small-error">Your squffy is already in school.</span>';
	} elseif(!$squffy->isAbleToLearn()) {
		echo '<span class="small-error">Your squffy cannot go to school right now.</span>';
	} elseif(strlen($worker_options) < 1) {
		echo '<span class="small-error">You have no squffies available to act as teacher.</span>';
	} else {
		echo 'Teacher: <select size="1" name="teacher_id">
		' . $worker_options . '
		</select>
		Degree: <select size="1" name="degree_id">';
		$query = "SELECT * FROM degrees";
		$result = runDBQuery($query);
		while($d = @mysql_fetch_assoc($result)) {
			echo '<option value="' . $d['degree_id'] . '">' . $d['degree_name'] . '</option>';
		}
		echo '</select>
		<input type="submit" class="submit-input" name="taught" value="Teach" />';
		if($squffy->isTaught()) {
        	echo '</td></tr><tr class="' . ($cur == "odd" ? "even" : "odd") . '"><td></td><td class="text-left"><span class="small"><b>Note</b>: This squffy is already a 
			<b>' . $squffy->getDegreeName() . '</b>. You would be replacing the old degree.</span>';
		}
	}    
	echo '</td></tr>';
}

//Allow user to purchase hire rights if: not already owned, hiring available, and able to work
if(!$squffy->canWorkFor($userid)) { ?>
    <tr<?php $cur = row($cur); ?>>
    <th class="content-miniheader width150">Hire</th>
    <td class="text-left">
    <?php  if($squffy->isHireable() && $squffy->isAbleToWork()) {
		$price = $squffy->getHirePrice();
		
		if($price->getItemPrice() || $price->getItemPrice() === "0") {
		 ?>
			<input type="radio" name="h_cost" value="item" /> <?php echo $price->getItemPrice() . ' ' . $price->getItemName() . 's'; 
		}
		if($price->getSDPrice() || $price->getSDPrice() === "0") {?>
    		<input type="radio" name="h_cost" value="sd" /> <?php echo $price->getSDPrice(); ?> Squffy Dollars
        <?php } ?>
        
        <input type="submit" class="submit-input" name="buy_hire" value="Purchase right to hire" /></td> </tr>
        <tr><td></td><td class="text-left"><span class="small"><b>Note</b>: Squffy must be assigned to a job within 30 minutes.</span><br /><br />
    <?php } elseif (!$squffy->isHireable()) {
		echo '<span class="small-error">This squffy is not available for hire.</span>';
	} elseif(!$squffy->isAbleToWork()) {
		echo '<span class="small-error">This squffy cannot work right now.</span>';
	}
	?>
    </td></tr>
<?php 
} 

//Allow user to purchase breeding rights if: not already owned, breeding available, and able to breed
if(!$squffy->canBreedFor($userid)) { ?>
    <tr<?php $cur = row($cur); ?>>
    <th class="content-miniheader width150">breed to</th>
    <td class="text-left">
    <?php  if($squffy->isBreedable() && $squffy->isAbleToWork()) {
		$price = $squffy->getBreedPrice(); 
		if($price->getItemPrice() || $price->getItemPrice() === "0") {?>
    	<input type="radio" name="b_cost" value="item" /> <?php echo $price->getItemPrice() . ' ' . $price->getItemName() . 's'; 
		}
		if($price->getSDPrice() || $price->getSDPrice() === "0") {?>
    	<input type="radio" name="b_cost" value="sd" /> <?php echo $price->getSDPrice(); ?> Squffy Dollars<?php } ?>
        <input type="submit" class="submit-input" name="buy_breed" value="Purchase right to breed" /></td> </tr>
        <tr><td></td><td class="text-left"><span class="small"><b>Note</b>: Squffy must be bred within 30 minutes.</span><br /><br />
    <?php } elseif (!$squffy->isBreedable()) {
		echo '<span class="small-error">This squffy is not available for breeding.</span>';
	} elseif(!$squffy->isAbleToWork()) {
		echo '<span class="small-error">This squffy cannot breed right now.</span>';
	}
	?>
    </td></tr>
<?php 
} 

else { ?>
    <tr<?php $cur = row($cur); ?>>
    <th class="content-miniheader width150">breed to</th>
    <td class="text-left">
    <?php
	$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR breeding_rights = $userid";
	$squffies = Squffy::getSquffies($query);
	$breed_options = "";
	foreach($squffies as $worker) {
		if(!$worker->canBreedFor($userid)) { continue; }
		if(!$worker->isAbleToWork()) { continue; }
		if($worker->getID() == $id) { continue; }
		if($worker->getGender() == $squffy->getGender()) { continue; }
		if($worker->hasMate() && $worker->getMateID() != $id) { continue; }
		if($squffy->hasMate() && $squffy->getMateID() != $worker->getID()) { continue; }
		$breed_options .= '<option value="' . $worker->getID() . '">' . $worker->getName() . '</option>';
	}
	
	if(strlen($breed_options) > 0) {
	?>
		<select name="parent_id" size="1"><?php echo $breed_options; ?></select> <input type="submit" class="submit-input" name="breed" value="Breed" />
    <?php
    } elseif(strlen($breed_options) < 1) {
		echo '<span class="small-error">You have no squffies available to breed with ' . $squffy->getName() . '.</span>';
    } elseif(!$squffy->isAbleToWork()) {
		echo '<span class="small-error">This squffy cannot breed right now.</span>';
	}
	?>
    </td></tr>

<?php }

//Allow user to request as mate, if available
if(!$squffy->hasMate()) { 
	$query = "SELECT * FROM squffies WHERE squffy_owner = $userid";
	$squffies = Squffy::getSquffies($query);
	$options = "";
	foreach($squffies as $mate) {
		if(!$squffy->canMateTo($mate)) { continue; }
		$options .= '<option value="' . $mate->getID() . '">' . $mate->getName() . '</option>';
	}
	?>    
    <tr<?php $cur = row($cur); ?>>
    <th class="content-miniheader width150">Mate to</th>
    <td class="text-left">
	<?php
	if(strlen($options) > 0) {
		echo '
		<select size="1" name="mate_id">
		' . $options . '
		</select>
		<input type="submit" name="set_mate" class="submit-input" value="' . ($squffy->getOwnerID() == $userid ? 'Set as mate' : 'Request as mate'). '" />';
	} else {
		echo '<span class="small-error">You have no squffies available as mates.</span>';
	}
    echo '</td></tr>';
	
	$youRequested = false;
	$requests = MatingNotification::getRequests($id, $userid);
	if(sizeof($requests) > 0) {
		foreach($requests as $request) {
			echo '<tr';
			$cur = row($cur);
			echo '><td></td><td class="text-left">';
			if($request->getUserID() == $userid) { 
				$mate = Squffy::getSquffyByID($request->getSentSquffy());
				if($squffy->getOwnerID() == $userid) {
					echo $request->getUserLink() . ' sent a mating request from ' . $mate->getLink(); 
					echo ' <input type="submit" class="submit-input" value="Accept ' . $mate->getName() . '" name="accept-mate" /> 
					<input type="submit" class="submit-input" value="Reject ' . $mate->getName() . '" name="reject-mate" />
					<input type="hidden" name="' . $mate->getName() . '" value="' . $mate->getID() . '" />';
				}
			} else {
				if($squffy->getOwnerID() == $userid) {
					$mate = Squffy::getSquffyByID($request->getRequestedSquffy());
					echo 'You sent a mating request to ' . $mate->getLink();
				} else {
					$mate = Squffy::getSquffyByID($request->getSentSquffy());
				 	echo 'You sent a mating request from ' . $mate->getLink(); 
				}
			}
			echo '</td></tr>';
		}
	}
}
?>
</form>
</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>