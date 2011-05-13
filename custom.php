<?php
$selected = "squffies";
$forLoggedIn = true;
include("./includes/header.php");

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
} elseif(isset($_POST['squffy_name'])) {	
	//Create squffy
	$name = $_POST['squffy_name'];
	$gender = $_POST['gender'];
	$design = Design::GetDesign($_POST['design']);
	$id = Squffy::CreateCustom($name, $gender, $design, $userid);
	
	//Remove item
	$item = $_POST['use_item'];
	$inventory[$item] = $inventory[$item] - 1;
	$user->updateInventory($item, -1);
	$query = "UPDATE inventory SET $item = $item - 1 WHERE user_id = $userid;";
	runDBQuery($query);
	
	displayNotices(array("Your new custom has just been created! <a href='view_squffy.php?id=$id'>See $name's page here</a>."));
} elseif(isset($_POST['use_item'])) {
	$item = $_POST['use_item'];
	$item_info = Item::CustomInfo($item);
	
	echo '<form action="custom.php" method="post">
	<table class="width100p"><tr><th colspan="4" class="content-header">Pick a Design</th></tr>';
	if($inventory[$item] > 0) {
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
	}
	if($num %4 > 0) { echo $add . '</tr>'; }
	echo '<tr><td colspan="4">
	Name: <input type="text" class="margin-bottom-small" name="squffy_name" /><br />
	Gender: <input type="radio" checked class="margin-bottom-small" name="gender" value="M" /> Male <input type="radio" name="gender" value="F" /> Female<br />
	<input type="submit" class="submit-input margin-top-small" value="Create custom" name="create" />
	<input type="hidden" name="use_item" value="' . $item . '" />
	</table></form>';
}

include('./includes/footer.php');
?>