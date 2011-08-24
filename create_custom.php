<?php
$selected = 'squffies';
$forLoggedIn = true;
include("./includes/header.php");

$tree = $user->canMakeFreeTreeSquffy();
$ground = $user->canMakeFreeGroundSquffy();
$inventory = $user->getInventory();

if(isset($_POST['make_tree'])) { 
	include('./create_custom_tree.php');
}

if(isset($_POST['make_ground'])) { 
	include('./create_custom_ground.php');
}

if(isset($_POST['make_item'])) { 
	include('./create_custom_item.php');
}

if(isset($_POST['create'])) {
	include('./scripts/make_custom.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<div class="content-header width100p"><b>Create custom</b></div>
<div class="padding-10">

<?php if($tree) { ?>
<form action="create_custom.php" method="post">
Every user gets to create one free custom tree squffy!  Create yours now?<br />
<input type="submit" class="submit-input margin-top-small" name="make_tree" value="Create free tree squffy" />
</form><br /><br />
<?php } ?>

<?php if($ground) { ?>
<form action="create_custom.php" method="post">
Every user gets to create one free custom ground squffy!  Create yours now?<br />
<input type="submit" class="submit-input margin-top-small" name="make_ground" value="Create free ground squffy" />
</form><br /><br />
<?php } ?>

<?php
$options = '';
$items = Item::getItemList();
foreach($items as $item) {
	if($item->canMakeCustom()) {
		$col = $item->getColumnName();
		if($inventory[$col] > 0) {
			$options .= '<option value="' . $item->getID() . '">' . $item->getName() . ' (Owned: ' . $inventory[$col] . ')</option>';
		}
	}
}

if(strlen($options) < 1 && !$tree && !$ground) {
	echo '</div><div class="errors">You have created your free squffies and have no custom seeds to spend.</div>'; 
} elseif(strlen($options) > 0) {
	echo '<form action="create_custom.php" method="post">
	Choose one of your custom seeds to use to make your custom squffy:<br />
	<select size="1" name="use_item" class="margin-top-small"> ' . $options . '</select><br />
    <input type="submit" value="Pick a design to create" name="make_item" class="margin-top-small submit-input" />
	</form>';
}
?>

</div>
<?php
include('./includes/footer.php');
?>

<?php
/*

$inventory = $user->getInventory();

//Starting out: pick a custom making item to use
if(!isset($_POST['use_item'])) {
	?>
	
	<div class='text-center width100p'><h1>Create Custom Squffy</h1></div>
	
	<?php
	$options = '';
	$items = Item::getItemList();
	foreach($items as $item) {
		if($item->canMakeCustom()) {
			$col = $item->getColumnName();
			if($inventory[$col] > 0) {
				$options .= '<option value="' . $col . '">' . $item->getName() . ' (Owned: ' . $inventory[$col] . ')</option>';
			}
		}
	}
	
	if(strlen($options) < 1 && !$user->canMakeFreeTreeSquffy()) {
		//TODO no custom making items
	} else {
	?>
    <form action="create_custom.php" method="post">
	<select size="1" name="use_item">
	<?php echo $options; ?>
	</select><br />
    <input type="submit" value="Pick a design to create" class="margin-top-small submit-input" />
	</form>
	<?php if($user->canMakeFreeTreeSquffy()){ ?>
		<form action="create_custom.php" method="post">
		<input type="hidden" value="double_acorn" name="use_item" />
		<input type="hidden" name="free_squffy_type" value="tree" />
	    <input type="submit" name="free_squffy" value="Make Free Tree Squffy" class="margin-top-small submit-input" />
		</form>
	<?php }
	if($user->canMakeFreeGroundSquffy()){ 
	?>
		<form action="create_custom.php" method="post">
		<input type="hidden" value="double_seed" name="use_item" />
		<input type="hidden" name="free_squffy_type" value="ground" />
	    <input type="submit" name="free_squffy" value="Make Free Ground Squffy" class="margin-top-small submit-input" />
		</form>

	<?php
		}
	}
} elseif(isset($_POST['squffy_name'])) {	
	//Create squffy
	$name = $_POST['squffy_name'];
	$gender = $_POST['gender'];
	$design = Design::getDesignByID($_POST['design']);
	$id = Squffy::CreateCustom($name, $gender, $design, $userid);
	$update_item = true;
	
	if(isset($_POST['free_squffy'])){
		$update_item = $user->useFreeSquffy($_POST['free_squffy_type']); //if update_item is true, use user's items from inventory.
	}
	if($update_item){
		//Remove item
		$item = $_POST['use_item'];
		$inventory[$item] = $inventory[$item] - 1;
		$user->updateInventory($item, -1);
		$query = "UPDATE inventory SET $item = $item - 1 WHERE user_id = $userid;";
		runDBQuery($query);
	}
	displayNotices(array("Your new custom has just been created! <a href='view_squffy.php?id=$id'>See $name's page here</a>."));
} elseif(isset($_POST['use_item'])) {
	$item = $_POST['use_item'];
	$update_item = true;
	
	$item_info = Item::CustomInfo($item);
	echo '<form action="create_custom.php" method="post">
	<table class="width100p"><tr><th colspan="4" class="content-header">Pick a Design</th></tr>';
	if($inventory[$item] > 0 || $user->canMakeFreeTreeSquffy()) {
		$designs = Design::GetUserDesigns($userid);
		$num = sizeof($designs);
		$add = '';
		if($num %4 == 3) { $add = '<td></td>'; }
		elseif($num %4 == 2) { $add = '<td></td><td></td>'; }
		elseif($num %4 == 1) { $add = '<td></td><td></td><td></td>'; }
		
		$i = 0;
		foreach($designs as $design) {
			$numTraits = $design->getNumTraits();
			
			if($i % 4 == 0) { echo '<tr>'; }
			echo '<td class="text-center vertical-top width200"><b>' . $design->getName() . '</b><br />
			<img src="' . $design->getThumbnail() . '" /><br />';
			if($numTraits <= $item_info['num'] && $design->getSpecies() == $item_info['species']) { echo '<input type="radio" name="design" value="' . $design->getID() . '"> Use design'; }
			if($design->getSpecies() != $item_info['species']) { echo '<span class="small-error">Wrong species!</span><br />'; }
			if($numTraits> $item_info['num']) { echo '<span class="small-error">Too many traits!</span>'; }
			echo '</td>';
			$i++;
			if($i % 4 == 0) { echo '</tr>'; }
		}
		if($num %4 > 0) { echo $add . '</tr>'; }
	}
	
	echo '<tr><td colspan="4">
	Name: <input type="text" class="margin-bottom-small" name="squffy_name" /><br />
	Gender: <input type="radio" checked class="margin-bottom-small" name="gender" value="M" /> Male <input type="radio" name="gender" value="F" /> Female<br />
	<input type="submit" class="submit-input margin-top-small" value="Create custom" name="create" />
	<input type="hidden" name="use_item" value="' . $item . '" />';
	if(isset($_POST['free_squffy'])){ 
		echo '<input type="hidden" name="free_squffy" />
				<input type="hidden" name="free_squffy_type" value="'.$_POST['free_squffy_type'].'" />';
		}
	echo '</table></form>';
}*/
?>