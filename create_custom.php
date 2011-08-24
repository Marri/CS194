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