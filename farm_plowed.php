<?php
echo 'Your plot is plowed, but has nothing planted in it currently. You can now place fertilizer or skip straight to planting seeds.<br /><br />


Spread fertilizer?  This requires between one and two squffies and a bag of fertilizer.<br /><br />';
$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR hire_rights = $userid";
$squffies = Squffy::getSquffies($query);
$work_options = '';
$num = 0;
foreach($squffies as $squffy) { 
	if(!$squffy->isAbleToWork(10)) { continue; }
	if(!$squffy->canWorkFor($userid)) { continue; }
	$work_options .= '<option value="' . $squffy->getID() . '">' . $squffy->getName() .'</option>'; 
	$num++;
}
if($farm->isFertilized()) {
	echo '<span class="small-error">You have already fertilized this field.</span><br /><br />';
} elseif($user->getAmount('fertilizer') < 1) {
	echo '<span class="small-error">You have no fertilizer.</span><br /><br />';
} elseif(strlen($work_options) < 1) {
	echo '<span class="small-error">You have no available workers. Hire some?</span><br /><br />';
} else {			
	echo '<form action="farm.php?id=' . $id . '" method="post">
	<select name="worker[]" multiple="multiple" class="margin-top-small" size="' . $num . '">';
	echo $work_options . '</select><br />
	<input type="submit" name="fertilize" value="Fertilize this plot" class="margin-top-small submit-input" />
	</form><br /><br />';
}


echo 'Plant seeds?  This requires between one and four squffies and between one and five bags of seeds.<br /><br />';
if(strlen($work_options) < 1) {
	echo '<span class="small-error">You have no available workers. Hire some?</span><br /><br />';
} else {
	$seed_options = '';
	$item_list = Item::getItemList();
	foreach($item_list as $item) {
		if(!$item->isSeed()) { continue; }
		if($user->getAmount($item->getColumnName()) < 1) { continue; }
		$seed_options .= '<option value="' . $item->getID() . '">' . $item->getName() .'</option>';
	}
	if(strlen($seed_options) > 0) {
		echo '<form action="farm.php?id=' . $id . '" method="post">
		Plant seeds: <select name="num_seeds" size="1">';
		for($i = 1; $i < 6; $i++) { echo "<option value='$i'>$i</option>"; }
		echo '</select> <select name="seed" size="1">' . $seed_options . '</select><br />
		<select name="worker[]" class="margin-top-small" multiple="multiple" size="' . $num . '">';
		echo $work_options . '</select><br />
		<input type="submit" name="plant" value="Plant this plot" class="margin-top-small submit-input" />
		</form>';
	} else {
		echo '<span class="small-error">You have no available bags of seeds.</span>';
	}
}	
?>