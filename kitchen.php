<?php
$selected = "squffies";
include("./includes/header.php");

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Community Kitchen</b></div>
<div class='npc'>Kitchen</div>
&nbsp;Some stuff about how you can use the kitchen to make food that is more filling.<br /><br />

<?php if($loggedin) { ?>
<form action="school.php" method="post">
&nbsp;Cook: <select size="1" name="cook_id">
<?php
$query = "SELECT * FROM `squffies` WHERE squffy_owner = $userid OR hire_rights = $userid;";
$squffies = Squffy::getSquffies($query);
foreach($squffies as $squffy) {
	if(!$squffy->canWorkFor($userid)) { continue; }
	if(!$squffy->isAbleToWork()) { continue; }
	echo '<option value="">' . $squffy->getName().'</option>';
}
?>
</select><br />
<table class="width100p">
<?php
$query = "SELECT * FROM `recipes`";
$result = runDBQuery($query);
$i = 0;
while($info = @mysql_fetch_assoc($result)) {
	$recipe = new Recipe($info);
	$recipe->fetchNames();
	$ings = $recipe->getIngredients();
	
	if($i % 4 == 0) { echo '<tr>'; }
	echo '<td class="width200 vertical-top"><div class="bordered padding-5">
	<img src="./images/items/' . strtolower(str_replace(" ","",$recipe->getName())) . '.png" class="item" alt="" /><br />
	<b>' . $recipe->getName() . '</b><br />';
	foreach($ings as $ing) {
		echo '&nbsp;&nbsp;' . $ing['amount'] . ' ' . $ing['name'] . 's<br />';
	}
	echo '<br /><input type="radio" value=""> Use this recipe</div></td>';
	if($i % 4 == 3) { echo '</tr>'; }
	$i++;
}
while ($i % 4 != 3) { echo '<td></td>'; $i++; } 
?></tr>
</table>
<input type="submit" name="learn" class="submit-input margin-top-small margin-left-small" value="Start cooking!" />
</form>

<?php
}

include('./includes/footer.php');
?>