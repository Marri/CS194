<?php
$selected = "squffies";
$js[] = 'edit_squffy';
include("./includes/header.php");
include('./objects/personality.php');
include('./objects/squffy.php');

$id = getID("id");
$squffy = Squffy::getSquffyByIDExtended
	($id, 
	array(
		Squffy::FETCH_FAMILY, 
		Squffy::FETCH_FULL_APPEARANCE, 
		Squffy::FETCH_PERSONALITY, 
		Squffy::FETCH_SPECIES, 
		Squffy::FETCH_ITEMS, 
		Squffy::FETCH_DEGREE
	)
);
if($squffy == NULL) { 
	displayErrors(array("That squffy does not exist."));
	include('./includes/footer.php');
	die();
}
	
$name = $squffy->getName();
$breedable = $squffy->isBreedable();
$hireable = $squffy->isHireable();
//$hireSD = $squffy->getHireSD();

$errors = array();
$notices = array();
include('./scripts/squffy_actions.php');
displayErrors($errors);
displayNotices($notices);

$item_options = "";
$query = "SELECT item_id, item_name FROM items";
$result = runDBQuery($query);
while($item = @mysql_fetch_assoc($result)) {
	$item_options .= '<option value="' . $item['item_id'] . '">' . $item['item_name'] . '</option>';
}
?>
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<table class="content-table">
<tr><th colspan="2" class="content-header">
Edit <?php echo $squffy->getName(); ?>
</th></tr>
<tr><td>Name</td><td><input type='text' name="squffy_name" value="<?php echo $name; ?>" /></td></tr>

<tr><td class="width200">Available for hire?</td><td><input type='radio' name="hireable" class="hireable" value='y'<?php checked($hireable); ?> /> Yes <input type='radio' name="hireable" class="hireable" value='n'<?php checked(!$hireable); ?> /> No</td></tr>
<tr class="hire_extra<?php if(!$hireable) echo ' hidden'; ?>"><td>Hire price in SD</td><td><input type='text' /></td></tr>
<tr class="hire_extra<?php if(!$hireable) echo ' hidden"'; ?>"><td>Hire price in items</td><td>
<input text="text" /> <select size="1"><?php echo $item_options ?></select>
</td></tr>

<tr><td>Available for breeding?</td><td><input type='radio' name="breedable" class='breedable' value='y'<?php checked($breedable); ?> /> Yes <input name="breedable" class='breedable' type='radio' value='n'<?php checked(!$breedable); ?> /> No</td></tr>
<tr class="breed_extra<?php if(!$breedable) echo ' hidden"'; ?>"><td>Breeding price in SD</td><td><input type='text' /></td></tr>
<tr class="breed_extra<?php if(!$breedable) echo ' hidden"'; ?>"><td>Breeding price in items</td><td>
<input text="text" /> <select size="1"><?php echo $item_options ?></select>
</td></tr>
<tr><td class="text-center" colspan="2">
<input class="submit-input" type='submit' name='update_squffy' value='Update <?php echo $squffy->getName(); ?>' />
</td></tr>
</table>
</form>

<?php /*
<tr><td class="text-center" colspan="2">
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<input class="submit-input" type='submit' value='Reset image' />
</form>
</td></tr>

</table>

<?php*/

include('./includes/footer.php');
?>