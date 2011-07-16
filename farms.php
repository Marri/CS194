<?php
$selected = "world";
$forLoggedIn = true;
include("./includes/header.php");

if(isset($_POST['purchase'])) {
	include('./scripts/purchase_farm.php');
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
foreach($farms as $farm) {
	echo $farm->getName() . ' (' . $farm->getDisplayType() . ')<br />';
}
?>
<br /><br />
<form action="farms.php" method="post" class="padding-5">
Purchase another plot?<br />
Name: <input type="text" name="plot_name" />
<select name="type" size="1">
	<option value="<?php echo Farm::FARM; ?>">Farm - 1 Chestnut</option>
	<option value="<?php echo Farm::ORCHARD; ?>">Orchard - 2 Chestnuts</option>
	<option value="<?php echo Farm::GARDEN; ?>">Garden - 3 Chestnuts</option>
</select>
<input type="submit" name="purchase" value="Buy another lot" class="submit-input" />
</form>

<?php
include("./includes/footer.php");
?>
