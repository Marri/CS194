<?php
$selected = "squffies";
include("./includes/header.php");
include("./objects/item.php");

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
	
	if(strlen($options) < 1) {
		//TODO no custom making items
	} else {
	?>
    <form action="custom.php" method="post">
	<select size="1" name="use_item">
	<?php echo $options; ?>
	</select><br />
    <input type="submit" value="Pick a design to create" class="margin-top-small submit-input" />
	</form>
	<?php
	}
} elseif(isset($_POST['use_item'])) {
}

include('./includes/footer.php');
?>