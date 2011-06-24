<?php
$selected = "world";
$js[] = 'hoard';
$forLoggedIn = true;
include("./includes/header.php");

$item_list = Item::getItemList();
$inventory = $user->getInventory();
$cur = 'odd';
?>

<div class='content-header width100p'><b>Item Hoard</b></div>
<table class="width100p" cellspacing="0">
<tr><td colspan="4">&nbsp;Pick a pile: 
<select size="1" name="filter" id="filter">
<option value="">All</option>
<option value="1">Nuts</option>
</select>
</td></tr>
<tr><th class="content-subheader" colspan="2">Item</th><th class="content-subheader">Amount</th><th class="content-subheader">Description</th></tr>
<?php
foreach($item_list as $item) {
	$col = $item->getColumnName();
	$key = str_replace("_", "", $col);
	$img = "../../images/items/$key.png";
	if($inventory[$col] > 0) {
?>
	<tr class="<?php $cur = classRow($cur);?> pile<?php echo $item->getType(); ?> item">
	<td class="text-center width100 vertical-top"><img class="item small-bordered" src="<?php echo $img; ?>" alt="<?php echo $item->getName(); ?>" /></td>
    <td class="text-center vertical-top"><b><?php echo $item->getName(); ?></b></td>
    <td class="text-center width100 vertical-top"><?php echo $inventory[$col]; ?></td>
    <td class="text-left vertical-top"><?php echo $item->getDescription(); ?></td>
    </tr>
<?php
	}
}
?>
</table>

<?php
include('./includes/footer.php');
?>