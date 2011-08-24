<?php
echo 'Your plot is currently growing ';
echo '<b>' . $farm->getNumCrops() . '</b> bags of ';
$food = $farm->getFoodID();
$food = Item::getItemByID($food);
$name = $food->getName();
$name = substr($name, 7);
$name = substr($name, 0, strlen($name) - 6);
echo '<b>'.pluralize($name).'</b>';
echo '.<br />
It will be ready to harvest at <b>' . date("g:i a \o\\n F j, Y", strtotime($farm->getDateRipe())) . '</b>.<br /><br />
Dryness: <b>' . $farm->getDryness() . '%</b><br />
Weeds: <b>' . $farm->getWeeds() . '%</b><br /><br />';

$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR hire_rights = $userid";
$squffies = Squffy::getSquffies($query);
$work_options = '';
foreach($squffies as $squffy) { 
	if(!$squffy->isAbleToWork(5)) { continue; }
	if(!$squffy->canWorkFor($userid)) { continue; }
	$work_options .= '<option value="' . $squffy->getID() . '">' . $squffy->getName() .'</option>'; 
}

if($farm->getDryness() > 0) {
	echo 'Water farm? You need one squffy and a water pail.<br /><br />';
	if(strlen($work_options) < 1) {
		echo '<span class="small-error">You have no workers available.</span><br /><br />';
	} elseif($user->getAmount('water_pail') < 1) {
		echo '<span class="small-error">You have no water pail available.</span><br /><br />';
	} else {
		echo '<form action="farm.php?id=' . $id . '" method="post">
		Worker: <select name="worker" size="1">' . $work_options . '</select><br />
		<input type="submit" name="water" class="submit-input margin-top-small" value="Water plot" />
		</form>
		<br /><br />';
	}
}

if($farm->getWeeds() > 0) {
	echo 'Weed farm? You need one squffy.<br /><br />';
	if(strlen($work_options) < 1) {
		echo '<span class="small-error">You have no workers available.</span><br /><br />';
	} else {
		echo '<form action="farm.php?id=' . $id . '" method="post">
		Worker: <select name="worker" size="1">' . $work_options . '</select><br />
		<input type="submit" name="weed" class="submit-input margin-top-small" value="Weed plot" />
		</form>
		<br /><br />';
	}
}
/*
Plow the land?  This requires between one and four squffies and a hoe.<br /><br />';
$query = "SELECT * FROM squffies WHERE squffy_owner = $userid OR hire_rights = $userid";
$squffies = Squffy::getSquffies($query);
if(sizeof($squffies) < 1) {
	echo '<span class="small-error">You have no available workers. Hire some?</span>';
} else {
	$options = '';
	$num = 0;
	foreach($squffies as $squffy) { 
		if(!$squffy->isAbleToWork(10)) { continue; }
		if(!$squffy->canWorkFor($userid)) { continue; }
		$options .= '<option value="' . $squffy->getID() . '">' . $squffy->getName() .'</option>'; 
		$num++;
	}
	
	echo '<form action="farm.php?id=' . $id . '" method="post">
	<select name="worker[]" multiple="multiple" size="' . $num . '">';
	echo $options . '</select><br />
	<input type="submit" name="plow" value="Plow this plot" class="margin-top-small submit-input" />
	</form>';
}*/
?>