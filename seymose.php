<?php
$selected = 'world';
include("./includes/header.php");

if($loggedin && isset($_POST['buying'])) {
	include('./scripts/buy_seymose.php');	
}

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Seymose the Trader</b></div>
<div class='text-center'><img class='margin-top-small' src='./images/npcs/seymose.jpg' /></div>
&nbsp;'Ello an' welcome! What're ye lookin' to do 'ere today?<br /><br />

<table class="width100p" cellspacing="0">
<tr><th colspan="4" class="content-subheader">buy my wares</th></tr>
<?php
$items = Item::getSeymoseList();
foreach($items as $item) { 
	$col = $item->getColumnName();
	$key = str_replace("_", "", $col);
	$img = "../../images/items/$key.png";
	echo '<tr>
	<td class="width100 text-center"><img class="item " src="' . $img . '" alt="' . $item->getName() . '" /></td>
	<td class="width300">' . $item->getName() . '</td>';
	if($loggedin) {
		$price = $item->getSeymosePrice();
		$id = $item->getSeymoseItem();
		$item_name = Item::getItemNameFromID($id);
		if($price > 1) { $item_name = pluralize($item_name); }
		echo '<td>
		<form action="seymose.php" method="post">
		<input type="hidden" name="buying_id" value="' . $item->getID() . '" />
		<input type="submit" name="buying" value="Buy for ' . $price . ' ' . $item_name . '" class="submit-input" />
		</form></td>';
	}
	echo '</tr>';
}
?>
</table>

<?php
include('./includes/footer.php');
?>