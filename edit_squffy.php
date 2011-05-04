<?php
$selected = "squffies";
include("./includes/header.php");
include('./objects/personality.php');
include('./objects/squffy.php');

$errors = array();
$notices = array();

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
if($squffy == NULL) { $errors[] = "That squffy does not exist."; }

displayErrors($errors);
displayNotices($notices);

if($squffy == NULL) {
	include('./includes/footer.php');
	die();
}

$item_options = "";
$query = "SELECT item_id, item_name FROM items";
$result = runDBQuery($query);
while($item = @mysql_fetch_assoc($result)) {
	$item_options .= '<option value="' . $item['item_id'] . '">' . $item['item_name'] . '</option>';
}
?>
<table class="content-table">
<tr><th colspan="2" class="content-header">
Edit <?php echo $squffy->getName(); ?>
</th></tr>
<tr><td>Name</td><td><input type='text' name="squffy_name" value="<?php echo $squffy->getName(); ?>" /></td></tr>

<tr><td>Available for hire?</td><td><input type='radio' value='y'<?php checked($squffy->isHireable()); ?> /> Yes <input type='radio' value='n'<?php checked(!$squffy->isHireable()); ?> /> No</td></tr>
<tr><td>Hire price in SD</td><td><input type='text' /></td></tr>
<tr><td>Hire price in items</td><td>
<input text="text" /> <select size="1"><?php echo $item_options ?></select>
</td></tr>

<tr><td>Available for breeding?</td><td><input type='radio' value='y' /> Yes <input type='radio' value='n' /> No</td></tr>
<tr><td>Breeding price in SD</td><td><input type='text' /></td></tr>
<tr><td>Breeding price in items</td><td>
<input text="text" /> <select size="1"><?php echo $item_options ?></select>
</td></tr>
<tr><td class="text-center" colspan="2">
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<input class="submit-input" type='submit' value='Reset image' />
</form>
</td></tr>

</table>

<?php
include('./includes/footer.php');
?>