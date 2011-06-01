<?php
$selected = 'world';
include("./includes/header.php");

if(isset($_POST['carve'])) {
	include('./scripts/carve.php');
}

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Woodshop</b></div>
<div class='npc'>Woodshop</div>
&nbsp;Some stuff about how you can use the woodshop to make wooden tools.<br /><br />

<?php if($loggedin) { ?>
<form action="woodshop.php" method="post">
&nbsp;Carpenter: 
<?php
$query = "SELECT * FROM `squffies` WHERE squffy_owner = $userid OR hire_rights = $userid;";
$squffies = Squffy::getSquffies($query);
$options = '';
foreach($squffies as $squffy) {
	if(!$squffy->canWorkFor($userid)) { continue; }
	if(!$squffy->isAbleToWork()) { continue; }
	$options .= '<option value="' . $squffy->getID() . '">' . $squffy->getName().'</option>';
}

if(strlen($options) > 0) {
	echo '<select size="1" name="carpenter_id">' . $options;
	echo '</select>';
} else { echo '<span class="small-error">You have no squffies available to carve right now.</span>'; }
?>
<br />
&nbsp;Amount: <select name="batches" size="1">
<?php for($i = 1; $i <= 20; $i++) { echo '<option value="' . $i . '">' . $i . '</option>'; } ?>
</select><br />
<table class="width100p">
<?php
$i = 0;
$recipes = Recipe::getRecipes('woodshop');
foreach($recipes as $recipe) {
	$recipe->fetchNames();
	$ings = $recipe->getIngredients();
	
	if($i % 4 == 0) { echo '<tr>'; }
	echo '<td class="width200 vertical-top"><div class=" padding-5">
	<img src="./images/items/' . strtolower(str_replace(" ","",$recipe->getName())) . '.png" class="item" alt="" /><br />
	<b>' . $recipe->getName() . '</b><br />';
	foreach($ings as $ing) {
		echo '&nbsp;&nbsp;' . $ing['amount'] . ' ';
		if($ing['amount'] > 1) { echo pluralize($ing['name']); }
		else { echo $ing['name']; }
		echo '<br />';
	}
	echo '<br />
	<b>Time:</b> ' . $recipe->getTime() . ' hours per item<br />
	<b>Energy:</b> ' . $recipe->getEnergy() . ' energy per item<br /><br />
	<input type="radio" name="recipe" value="' . $recipe->getID() . '"> Make this item</div></td>';
	if($i % 4 == 3) { echo '</tr>'; }
	$i++;
}
if($i % 4 != 0) {
	while ($i % 4 != 0) { echo '<td></td>'; $i++; } 
	echo '</tr>';
}
?></tr>
</table>
<input type="submit" name="carve" class="submit-input margin-top-small margin-left-small" value="Start carving!" />
<br /><br />
</form>

<?php
}

include('./includes/footer.php');
?>