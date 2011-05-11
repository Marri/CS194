<?php
$selected = "squffies";
$forLoggedIn = true;
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
	include("./objects/design.php");
	$item = $_POST['use_item'];
	$item_info = Item::CustomInfo($item);
	
	echo '<table class="width100p"><tr><th colspan="4" class="content-header">Pick a Design</th></tr><tr>';
	if($inventory[$item] > 0) {
		$designs = Design::GetUserDesigns($userid);
		$num = sizeof($designs);
		$add = '';
		if($num == 3) { $add = '<td></td>'; }
		elseif($num == 2) { $add = '<td></td><td></td>'; }
		elseif($num == 1) { $add = '<td></td><td></td><td></td>'; }
		
		foreach($designs as $design) {
			$numTraits = $design->getNumTraits();
			
			echo '<td class="text-center vertical-top width200"><b>' . $design->getName() . '</b><br />
			<img src="./scripts/generate_user_design.php?thumbnail=true&design=' . $design->getID() . '" /><br />';
			if($numTraits <= $item_info['num'] && $design->getSpecies() == $item_info['species']) { echo '<input type="radio" name="design"> Use design'; }
			if($design->getSpecies() != $item_info['species']) { echo '<span class="small-error">Wrong species!</span><br />'; }
			if($numTraits> $item_info['num']) { echo '<span class="small-error">Too many traits!</span>'; }
			echo '</td>';
		}
	}
	echo $add . '</tr>
	<tr><td colspan="4">
	Name: <input type="text" class="margin-bottom-small" /><br />
	Gender: <input type="radio" checked class="margin-bottom-small" /> Male <input type="radio" /> Female<br />
	<input type="submit" class="submit-input margin-top-small" value="Create custom" />
	</table>';
}

include('./includes/footer.php');
?>