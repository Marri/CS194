<?php
$selected = 'world';
$cur = 'odd';
$forLoggedIn = true;
include("./includes/header.php");

if(isset($_POST['purchase'])) {
	include('./scripts/purchase_farm.php');
}

if(isset($_POST['convert'])) {
	include('./scripts/convert_seeds.php');
}

displayErrors($errors);
displayNotices($notices);
?>
<div class='content-header width100p'><b>Farmland and Orchards</b></div>

<div class='text-center'>
	<img src='./images/npcs/farm.jpg' />
</div>
<br /><br />

<?php
$farms = Farm::GetFarmsByUser($userid);
if(sizeof($farms) > 0) {
	echo '<table class="width100p" cellspacing="0">
	<tr><th colspan="4" class="content-subheader">Your farmland</th></tr>';
	foreach($farms as $farm) {
		echo '<tr';
		$cur = row($cur);
		echo '><td class="width50"></td>
		<td>' . $farm->getLink() . ' (' . $farm->getDisplayType() . ')</td>
		<td class="width300">' . $farm->getDisplayState() . '</td>
		<td class="width300">';
		if($farm->getNumWorkers() > 0) { echo $farm->getNumWorkers() . ' workers'; }
		echo '</td></tr>';
	}
	echo '</table>';
}
?>
<br />
<form action="farms.php" method="post" class="padding-10">
Purchase another plot?<br />
Name: <input type="text" name="plot_name" />
<select name="type" size="1">
	<option value="<?php echo Farm::FARM; ?>">Farm - 1 Chestnut</option>
	<option value="<?php echo Farm::ORCHARD; ?>">Orchard - 2 Chestnuts</option>
	<option value="<?php echo Farm::GARDEN; ?>">Garden - 3 Chestnuts</option>
</select>
<input type="submit" name="purchase" value="Buy another lot" class="submit-input" />
</form><br />

<?php
$items = Item::getItemList();
$inventory = $user->getInventory();
$options = '';
foreach($items as $item) {
	if(!$item->isGrowable()) { continue; }
	if($inventory[$item->getColumnName()] < 1) { continue; }
	$options .= '<option value="' . $item->getID() . '">' . $item->getName() . '</option>';
}

if(strlen($options) > 0) {
	?>
	<form action="farms.php" method="post" class="padding-10">
	Turn nuts to seeds?<br />
	Convert <select name="num_convert" size="1" class="margin-top-small">
	<?php for($i = 1; $i < 11; $i++) { echo "<option value='$i'>$i</option>"; } ?>
	</select> 
	<select name="food_convert" size="1" class="margin-top-small"><?php echo $options; ?></select> to seeds
	<input type="submit" name="convert" value="Turn to seeds" class="submit-input" />
	</form><br />
	<?php
}

include("./includes/footer.php");
?>
