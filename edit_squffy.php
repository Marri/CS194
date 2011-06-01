<?php
$forLoggedIn = true;
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
	)
);
if($squffy == NULL) { 
	displayErrors(array("That squffy does not exist."));
	include('./includes/footer.php');
	die();
}
if($squffy->getOwnerID() != $userid && !$user->isAdmin()) { 
	displayErrors(array("That is not your squffy."));
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

if($hire_sd == 'NULL') { $hire_sd = ''; }
if($hire_amount == 'NULL') { $hire_amount = ''; }
if($breed_amount == 'NULL') { $breed_amount = ''; }
if($breed_sd == 'NULL') { $breed_sd = ''; }

$title = 'Edit '.$squffy->getName();
$links = array(
	array('name'=>'basics', 'url'=>"view_squffy.php?id=" . $squffy->getID()),
	array('name'=>'appearance', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=appearance'),
	array('name'=>'personality', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=personality'),
	array('name'=>'history', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=history'),
	array('name'=>'family', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=family')
);

if($loggedin) {
	$links[] = array('name'=>'interact', 'url'=>"view_squffy.php?id=" . $squffy->getID() . '&view=interact');
	if($squffy->getOwnerID() == $userid || $user->isAdmin()) { 
		$links[] = array('name'=>'edit squffy', 'url'=>"edit_squffy.php?id=" . $squffy->getID()); 
	}
}

drawMenuTop($title, $links);

$item_options = "";
$items = Item::getItemList();
foreach($items as $item) {
	if($item->getID() == 2){ continue; }
	$item_options .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>';
}
$cur = "even";
?>
<img src="<?php echo $squffy->getURL(); ?>" />
<form action="edit_squffy.php?id=<?php echo $id; ?>" method="post">
<table class="width100p text-left squffy-table" cellspacing="0">
    <tr>
        <th class="content-subheader" colspan="4">update information</th>
    </tr>
    <tr<?php $cur = row($cur); ?>>
        <th class="width250 content-miniheader">Name</th>
        <td colspan="3"><input class="width100p" type='text' name="squffy_name" value="<?php echo $name; ?>" /></td>
    </tr>
    
    <?php if($squffy->isAdult()) { ?>
    <tr<?php $cur = row($cur); ?>>
        <th class="width250 content-miniheader">Available for hire?</th>
        <td class="text-left" colspan="3"><input type='radio' name="hireable" class="hireable" value='y'<?php checked($hireable); ?> /> Yes <input type='radio' name="hireable" class="hireable" value='n'<?php checked(!$hireable); ?> /> No</td>
    </tr>
	<tr<?php $cur = row($cur); ?>>
    	<th class="content-miniheader">Hire price in SD</th>
        <td colspan="3"><input class="width100 hire" type='text' name='hire_sd' value="<?php echo $hire_sd; ?>" <?php if(!$hireable) { echo 'disabled="disabled" '; } ?>/></td>
	</tr>
	<tr<?php $cur = row($cur); ?>>
    	<th class="content-miniheader">Hire price in items</th>
        <td colspan="3"><input text="text" class="width100 hire" name="hire_amount" value="<?php echo $hire_amount; ?>" <?php if(!$hireable) { echo 'disabled="disabled" '; } ?>/> <select size="1" name="hire_item" class="hire"<?php if(!$hireable) { echo 'disabled="disabled" '; } ?>><?php replace($hire_item, $item_options); ?></select></td>
	</tr>
    <tr<?php lastRow($cur); ?>>
    	<td></td>
        <td colspan="3" class="small"><b>Note</b>: Leave blank to disallow an option. Put 0 to allow free hiring.</td>
    </tr>
	<tr<?php $cur = row($cur); ?>>
		<th class="content-miniheader">Available for breeding?</th>
		<td colspan="3"><input type='radio' name="breedable" class='breedable' value='y'<?php checked($breedable); ?> /> Yes <input name="breedable" class='breedable' type='radio' value='n'<?php checked(!$breedable); ?> /> No</td>
	</tr>
	<tr<?php $cur = row($cur); ?>>
		<th class="content-miniheader">Breeding price in SD</th>
		<td colspan="3"><input class="width100 breed" type='text' name='breed_sd' value="<?php echo $breed_sd; ?>" <?php if(!$breedable) { echo 'disabled="disabled" '; } ?>/></td>
	</tr>
	<tr<?php $cur = row($cur); ?>>
		<th class="content-miniheader">Breeding price in items</th>
		<td colspan="3"><input text="text" class="width100 breed" name="breed_amount" value="<?php echo $breed_amount; ?>"<?php if(!$breedable) { echo 'disabled="disabled" '; } ?> /> <select size="1" name="breed_item" class="breed"<?php if(!$breedable) { echo 'disabled="disabled" '; } ?>><?php replace($breed_item, $item_options); ?></select></td>
	</tr>
    <tr<?php lastRow($cur); ?>>
    	<td></td>
        <td colspan="3" class="small"><b>Note</b>: Leave blank to disallow an option. Put 0 to allow free breeding.</td>
    </tr>
    <?php } ?>
	<tr<?php $cur = row($cur); ?>>
    	<td></td>
        <td colspan="3"><input class="submit-input margin-bottom-small margin-top-small" type='submit' name='update_squffy' value='Update <?php echo $name; ?>' /></td>
	</tr>

	<?php if($num > 0) { ?>
	<tr>
    	<th class="content-subheader" colspan="4">reorder appearance traits</th>
    </tr>
	<?php for($i = 0; $i < $num; $i++) { $cur = showTrait($num, $traits, $i, $cur); } ?>
	<tr<?php $cur = row($cur); ?>>
    	<td></td>
		<td class="text-left" colspan="3"><input type="submit" id="reorder" num="<?php echo $num; ?>" class="submit-input margin-bottom-small" value="Reorder traits" name="reorder" /></td>
	</tr>
	<?php } ?>

	<tr>
    	<th class="content-subheader" colspan="4">reset image</th>
	</tr>
	<tr<?php $cur = row($cur); ?>>
    	<td></td>
		<td class="text-left" colspan="3"><input type="submit" id="reset_image" class="submit-input margin-top-small" value="Reset image" name="reset_image" /></td>
	</tr>
</table>
</form>
</td></tr></table>

<?php
function replace($val, $options) {
	echo str_replace('value="' . $val . '"','value="' . $val . '" selected', $options);
}

function row($cur) {
	echo ' class="' . $cur . '"';
	return $cur == "odd" ? "even" : "odd";
}

function lastRow($cur) {
 	echo ' class="' . ($cur == "odd" ? "even" : "odd") . '"';
}

function showTrait($num, &$traits, $i, $cur) {
	echo '<tr';
	$cur = row($cur);
	echo '><th class="width250 content-miniheader" id="trait' . $i . 'name">' . $traits[$i]->getTitle() . '</th>
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
	return $cur;
}

include('./includes/footer.php');
?>