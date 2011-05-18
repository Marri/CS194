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
		Squffy::FETCH_SPECIES, 
		/*Squffy::FETCH_FAMILY, 
		Squffy::FETCH_FULL_APPEARANCE, 
		Squffy::FETCH_PERSONALITY, 
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

$t = $squffy->getAppearanceTraits();
$traits = array();
foreach($t as $trait) {
	if($trait->getSquare() != 'C') { $traits[] = $trait; }
}
$num = sizeof($traits);

include('./scripts/squffy_actions.php');
displayErrors($errors);
displayNotices($notices);

$title = 'Edit '.$squffy->getName();
$links = array(
	array('name'=>'basics', 'url'=>"view_squffy.php?id=" . $squffy->getID()),
	array('name'=>'appearance', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=appearance'),
	array('name'=>'personality', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=personality'),
	array('name'=>'history', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=history'),
	array('name'=>'family', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=family'),
	array('name'=>'interact', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=interact'),
	array('name'=>'edit squffy', 'url'=>"edit_squffy.php?id=" . $squffy->getID()),
);
drawMenuTop($title, $links);

$item_options = "";
$items = Item::getItemList();
foreach($items as $item) {
	if($item->getID() == 2){ continue; }
	$item_options .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>';
}
?>
<img src="<?php echo $squffy->getURL(); ?>" />
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<table class="width100p text-left">
<tr><th class="content-subheader" colspan="4">update information</th></tr>
<tr><th class="content-subheader">Name</th><td colspan="3"><input class="width100p" type='text' name="squffy_name" value="<?php echo $name; ?>" /></td>

</tr>
<tr><th class="width15 content-subheader">Available for hire?</th>
<td class="text-left" colspan="3"><input type='radio' name="hireable" class="hireable" value='y'<?php checked($hireable); ?> /> Yes <input type='radio' name="hireable" class="hireable" value='n'<?php checked(!$hireable); ?> /> No</td>

</tr>
<tr><th class="content-subheader">Hire price in SD</td><td colspan="3">
<input class="width100 hire" type='text' name='hire_sd' value="<?php echo $hire_sd; ?>" <?php if(!$hireable) { echo 'disabled="disabled" '; } ?>/></td>

</tr>
<tr><th class="content-subheader">Hire price in items</td><td colspan="3">
<input text="text" class="width100 hire" name="hire_amount" value="<?php echo $hire_amount; ?>" <?php if(!$hireable) { echo 'disabled="disabled" '; } ?>/> <select size="1" name="hire_item" class="hire"<?php if(!$hireable) { echo 'disabled="disabled" '; } ?>><?php replace($hire_item, $item_options); ?></select>
</td>

</tr>
<tr>
<th class="content-subheader">Available for breeding?</td>
<td colspan="3"><input type='radio' name="breedable" class='breedable' value='y'<?php checked($breedable); ?> /> Yes <input name="breedable" class='breedable' type='radio' value='n'<?php checked(!$breedable); ?> /> No</td>

</tr>
<tr>
<th class="content-subheader">Breeding price in SD</td>
<td colspan="3"><input class="width100 breed" type='text' name='breed_sd' value="<?php echo $breed_sd; ?>" <?php if(!$breedable) { echo 'disabled="disabled" '; } ?>/></td>

</tr>
<tr>
<th class="content-subheader">Breeding price in items</td>
<td colspan="3"><input text="text" class="width100 breed" name="breed_amount" value="<?php echo $breed_amount; ?>"<?php if(!$breedable) { echo 'disabled="disabled" '; } ?> /> 
<select size="1" name="breed_item" class="breed"<?php if(!$breedable) { echo 'disabled="disabled" '; } ?>><?php replace($breed_item, $item_options); ?></select>
</td>

</tr>
<tr><td></td><td colspan="3">
<input class="submit-input" type='submit' name='update_squffy' value='Update <?php echo $name; ?>' />
</td>

</tr>

<tr><th class="content-subheader" colspan="4">reorder appearance traits</th></tr>
<?php 
for($i = 0; $i < $num; $i++) {
	showTrait($num, $traits, $i);
}
?>
<tr><td></td>
<td class="text-left" colspan="3">
<input type="submit" id="reorder" num="<?php echo $num; ?>" class="submit-input" value="Reorder traits" name="reorder" /></td></tr>

<tr><th class="content-subheader" colspan="4">reset image</th></tr>
<tr><td></td>
<td class="text-left" colspan="3"><input type="submit" id="reset_image" class="submit-input" value="Reset image" name="reset_image" /></td></tr>

</table>
</form>
</td></tr></table>

<?php
/*
<tr><th class="content-subheader width50p" colspan="2">Edit Information</th><th class="content-subheader" colspan="4">Reorder Appearance Traits</th></tr>

<tr><td>Name</td><td><input class="width100p" type='text' name="squffy_name" value="<?php echo $name; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td class="width150">Available for hire?</td>
<td><input type='radio' name="hireable" class="hireable" value='y'<?php checked($hireable); ?> /> Yes <input type='radio' name="hireable" class="hireable" value='n'<?php checked(!$hireable); ?> /> No</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td>Hire price in SD</td><td><input class="width100 hire" type='text' name='hire_sd' value="<?php echo $hire_sd; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td>Hire price in items</td><td>
<input text="text" class="width100 hire" name="hire_amount" value="<?php echo $hire_amount; ?>" /> <select size="1" name="hire_item" class="hire"><?php replace($hire_item, $item_options); ?></select>
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>

<tr>
<td>Available for breeding?</td>
<td><input type='radio' name="breedable" class='breedable' value='y'<?php checked($breedable); ?> /> Yes <input name="breedable" class='breedable' type='radio' value='n'<?php checked(!$breedable); ?> /> No</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr>
<td>Breeding price in SD</td>
<td><input class="width100 breed" type='text' name='breed_sd' value="<?php echo $breed_sd; ?>" /></td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr>
<td>Breeding price in items</td>
<td><input text="text" class="width100 breed" name="breed_amount" value="<?php echo $breed_amount; ?>" /> 
<select size="1" name="breed_item" class="breed"><?php replace($breed_item, $item_options); ?></select>
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>
<tr><td class="text-center" colspan="2">
<input class="submit-input" type='submit' name='update_squffy' value='Update <?php echo $name; ?>' />
</td>
<?php $i = showTrait($num, $traits, $i); ?>
</tr>*/

function replace($val, $options) {
	echo str_replace('value="' . $val . '"','value="' . $val . '" selected', $options);
}

function showTrait($num, &$traits, $i) {
	if($i == $num) {
		echo '<td colspan="4" class="text-center">
		<input type="submit" id="reorder" num="' . $num . '" class="submit-input" value="Reorder traits" name="reorder" /></td>';
	} elseif($i < $num) {
		echo '<tr><th class="width200 content-subheader" id="trait' . $i . 'name">' . $traits[$i]->getTitle() . '</th>
		<td class="width80 text-center" id="trait' . $i . 'box"> 
			<div class="color-box" style="background-color: #' . $traits[$i]->getColor() . '"></div></td>
		<td class="width80" id="trait' . $i . 'color">' . $traits[$i]->getColor() .'</td>
		<td>
		<input type="hidden" name="trait' . $i . '" id="trait' . $i . '" value="' . $traits[$i]->getID() . '" />
		<a href="#"><img num="' . $i . '" src="./images/icons/arrow_up.png" class="moveArrowUp margin-right-small no-border float-left';
		if($i == 0) { echo ' invisible'; }
		echo '" /></a>
		<a href="#"><img num="' . $i . '" src="./images/icons/arrow_down.png" class="moveArrowDown margin-right-small no-border float-left';
		if($i == $num - 1) { echo ' invisible'; }
		echo '" /></a></td></tr>';
	} elseif ($i == $num + 1) {
		echo '<th colspan="4" class="content-subheader">Reset Image</td>';
	} elseif ($i == $num + 2) {
		echo '<td colspan="4" class="text-center">
		<input type="submit" id="reset_image" class="submit-input" value="Reset image" name="reset_image" /></td>';
	} else {
		echo '<td></td><td></td><td></td><td></td>';
	}
	$i++;
	return $i;
}

include('./includes/footer.php');
?>