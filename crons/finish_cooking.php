<?php
include('../includes/connect.php');
include('../objects/personality.php');
include('../objects/appearance.php');
include('../objects/cost.php');
include('../objects/recipe.php');
include('../objects/user.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `jobs_cooking` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_finished) >= 0';
$result = runDBQuery($query);

$workers = '';
while($info = @mysql_fetch_assoc($result)) {
	$id = $info['squffy_id'];
	$squffy = Squffy::getSquffyByID($id);	
	$workers .= ', ' . $id;
	
	$r_id = $info['recipe_id'];
	$recipe = Recipe::getRecipeByID($r_id);	
	$recipe->fetchNames();
	$made = $recipe->getMade();
	$batches = $info['amount'];
	$col = strtolower(str_replace(" ","_",$made));
	
	$userid = $info['user_id'];
	$user = User::getUserByID($userid);
	$user->updateInventory($col, $batches, true);
	User::cacheChanged($userid);
}

$query = 'DELETE FROM `jobs_cooking` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_finished) >= 0';
runDBQuery($query);

//Set cooks to not be cooking
if(strlen($workers) > 0) {
	$workers = substr($workers, 2);
	$query = "UPDATE `squffies` SET `is_working` = 'false' WHERE squffy_id IN ($workers)";
	runDBQuery($query);
}
?>