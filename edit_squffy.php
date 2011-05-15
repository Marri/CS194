<?php
$selected = "squffies";
$js[] = 'edit_squffy';
$css[] = 'squffy';
include("./includes/header.php");

$id = getID("id");
$squffy = Squffy::getSquffyByIDExtended
	($id, 
	array(
		Squffy::FETCH_FULL_APPEARANCE, 
		/*Squffy::FETCH_FAMILY, 
		Squffy::FETCH_FULL_APPEARANCE, 
		Squffy::FETCH_PERSONALITY, 
		Squffy::FETCH_SPECIES, 
		Squffy::FETCH_ITEMS, 
		Squffy::FETCH_DEGREE*/
	)
);
if($squffy == NULL) { 
	displayErrors(array("That squffy does not exist."));
	include('./includes/footer.php');
	die();
}
	
//Set all variables used so can be altered by the update script
$name = $squffy->getName();
$breedable = $squffy->isBreedable();
$hireable = $squffy->isHireable();
$hire = $squffy->getHirePrice();
$breed = $squffy->getBreedPrice();
$hire_sd = $hire->getSDPrice();
$hire_item = $hire->getItemID();
$hire_amount = $hire->getItemPrice();
$breed_sd = $breed->getSDPrice();
$breed_item = $breed->getItemID();
$breed_amount = $breed->getItemPrice();

include('./scripts/squffy_actions.php');
displayErrors($errors);
displayNotices($notices);

$item_options = "";
$items = Item::getItemList();
foreach($items as $item) {
	if($item->getID() == 2){ continue; }
	$item_options .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>';
}

$t = $squffy->getAppearanceTraits();
$traits = array();
foreach($t as $trait) {
	$traits[] = $trait;
}
$num = sizeof($traits);
$i = 0;
?>
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<table class="content-table">
<tr><th colspan="6" class="content-header">
Edit <?php echo $name; ?>
</th></tr>
<tr><th class="content-subheader width50p" colspan="2">Edit Information</th><th class="content-subheader" colspan="4">Reorder Appearance Traits</th></tr>

<tr><td>Name</td><td><input class="width100p" type='text' name="squffy_name" value="<?php echo $name; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td class="width150">Available for hire?</td>
<td><input type='radio' name="hireable" class="hireable" value='y'<?php checked($hireable); ?> /> Yes <input type='radio' name="hireable" class="hireable" value='n'<?php checked(!$hireable); ?> /> No</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr class="hire_extra<?php if(!$hireable) echo ' hidden'; ?>"><td>Hire price in SD</td><td><input class="width100" type='text' name='hire_sd' value="<?php echo $hire_sd; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr class="hire_extra<?php if(!$hireable) echo ' hidden"'; ?>"><td>Hire price in items</td><td>
<input text="text" class="width100" name="hire_amount" value="<?php echo $hire_amount; ?>" /> <select size="1" name="hire_item"><?php replace($hire_item, $item_options); ?></select>
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>

<tr>
<td>Available for breeding?</td>
<td><input type='radio' name="breedable" class='breedable' value='y'<?php checked($breedable); ?> /> Yes <input name="breedable" class='breedable' type='radio' value='n'<?php checked(!$breedable); ?> /> No</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr class="breed_extra<?php if(!$breedable) echo ' hidden"'; ?>">
<td>Breeding price in SD</td>
<td><input class="width100" type='text' name='breed_sd' value="<?php echo $breed_sd; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr class="breed_extra<?php if(!$breedable) echo ' hidden"'; ?>">
<td>Breeding price in items</td>
<td><input text="text" class="width100" name="breed_amount" value="<?php echo $breed_amount; ?>" /> 
<select size="1" name="breed_item"><?php replace($breed_item, $item_options); ?></select>
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td class="text-center" colspan="2">
<input class="submit-input" type='submit' name='update_squffy' value='Update <?php echo $name; ?>' />
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
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
function replace($val, $options) {
	echo str_replace('value="' . $val . '"','value="' . $val . '" selected', $options);
}

function showTrait($num, &$traits, $i) {
	if($i == $num) {
		echo '<td colspan="4" class="text-center"><input type="submit" class="submit-input" value="Reorder traits" name="reorder" /></td>';
	} elseif($i < $num) {
		echo '<td class="width125">' . $traits[$i]->getTitle() . '</td><td class="width50"> 
		<div class="color-box" style="background-color: #' . $traits[$i]->getColor() . '"></div></td>
		<td class="width80">' . $traits[$i]->getColor() .'</td>
		<td><img src="./images/icons/arrow_up.png" class="margin-right-small no-border float-left';
		if($i == 0) { echo ' invisible'; }
		echo '" />';
		if($i < $num - 1) { echo '<img src="./images/icons/arrow_down.png" class="margin-right-small no-border float-left" />'; }
		echo '</td>';
	} else {
		echo '<td></td><td></td><td></td><td></td>';
	}
	$i++;
	return $i;
}

include('./includes/footer.php');
?>