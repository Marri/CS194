<?php
$forLoggedIn = true;
$selected = 'world';
include("./includes/header.php");

if(isset($_POST['add_nuts'])) {
	$burying = $_POST['burying'];
	foreach($burying as $name) {
		$bury = $_POST[$name];
		if($bury > 0) {
			$user->updateInventory($name, -1 * $bury, true);
			$query = "UPDATE pantry SET $name = $name + $bury WHERE user_id = $userid";
			runDBQuery($query);
		}
	}
	$notices[] = 'Success! Your pantry has been stocked.';
}

if(isset($_POST['remove_nuts'])) {
	$unburying = $_POST['unburying'];
	foreach($unburying as $name) {
		$unbury = $_POST[$name];
		if($unbury > 0) {
			$user->updateInventory($name, $unbury, true);
			$query = "UPDATE pantry SET $name = $name - $unbury WHERE user_id = $userid";
			runDBQuery($query);
		}
	}
	$notices[] = 'Success! Your pantry has been raided.';
}

displayErrors($errors);
displayNotices($notices);

$query = "SELECT * FROM pantry WHERE user_id = $userid";
$result = runDBQuery($query);
$info = @mysql_fetch_assoc($result);

$item_list = Item::getItemList();
$inventory = $user->getInventory();
?>
<div class="content-header width100p"><b>Pantry</b></div>
<div class='text-center'><img class='' src='./images/npcs/pantry.jpg' /></div>

<form action="pantry.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th colspan="4" class="content-subheader">Your Pantry's Contents</th></tr>
<?php
foreach($info as $key => $val) { 
	if($key == 'user_id') { continue; }
	if($val < 1) { continue; }
	$col = $key;
	$src = str_replace("_", "", $col);
	$img = "../../images/items/$src.png";
	$name = myucfirst(str_replace("_", " ", $col));
	echo '<tr>
	<td class="width100 text-center"><img class="item " src="' . $img . '" alt="' . $name . '" /></td>
	<td class="width300 text-center">' . $name . '<input type="hidden" name="unburying[]" value="' . $col . '" /></td>
	<td class="width200 text-center">' . $val . ' in storage</td>
	<td>Remove <select name="' . $key . '" size="1">';
	for($i = 0; $i <= $val; $i++) { echo "<option value='$i'>$i</option>"; }
	echo '</select></td>';
	echo '</tr>';
}
?>
<tr><th colspan="4"><input class="submit-input" type="submit" name="remove_nuts" value="Remove food from storage" /></th></tr>
</table>
</form>
<br /><br />


<form action="pantry.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th colspan="4" class="content-subheader">Store more food</th></tr>
<?php
foreach($item_list as $item) {
	if(!$item->isFood()) { continue; }
	if($inventory[$item->getColumnName()] < 1) { continue; }
	
	$src = str_replace("_", "", $item->getColumnName());
	$img = "../../images/items/$src.png";
	
	echo '<tr>
	<td class="width100 text-center"><img class="item " src="' . $img . '" alt="' . $item->getName() . '" /></td>
	<td class="width300 text-center">' . $item->getName() . '<input type="hidden" name="burying[]" value="' . $item->getColumnName() . '" /></td>
	<td class="width200 text-center">' . $inventory[$item->getColumnName()] . ' in inventory</td>
	<td>Remove <select name="' . $item->getColumnName() . '" size="1">';
	for($i = 0; $i <= $inventory[$item->getColumnName()]; $i++) { echo "<option value='$i'>$i</option>"; }
	echo '</select></td></tr>';
}
?>
<tr><th colspan="4"><input class="submit-input" type="submit" name="add_nuts" value="put food in storage" /></th></tr>
</table><br /><br />
</form>
<?php
include('./includes/footer.php');
?>