<?php
echo 'Your plot has nothing in it currently.<br /><br />
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
}
?>