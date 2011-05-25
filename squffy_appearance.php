<?php
$t = $squffy->getAppearanceTraits();
$visible = array();
$carried = array();
foreach($t as $trait) {
	if($trait->getSquare() != 'C') { $visible[] = $trait; }
	else { $carried[] = $trait; }
}
?>
<table class="width100p squffy-table" cellspacing="0">
<tr><th colspan="5" class="content-subheader">Standard Colors</th></tr>
<tr class="even"><td class="content-miniheader width150">Base</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getBaseColor(); ?>"></div></td>
<td class="text-left" colspan="3"><?php echo $squffy->getBaseColor(); ?></td></tr>
<tr class="odd"><td class="content-miniheader width150">Eye</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getEyeColor(); ?>"></div></td>
<td class="text-left" colspan="3"><?php echo $squffy->getEyeColor(); ?></td></tr>
<tr class="even"><td class="content-miniheader width150">Feet & Ears</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getFootColor(); ?>"></div></td>
<td class="text-left" colspan="3"><?php echo $squffy->getFootColor(); ?></td></tr>
<?php 
$cur = "odd";
if(sizeof($visible) > 0) { ?>
<tr><th colspan="5" class="content-subheader">Visible Traits</th></tr>
<?php foreach($visible as $trait) { ?>
<tr<?php $cur = row($cur); ?>>
    <td class="content-miniheader width150"><?php echo $trait->getTitle(); ?></td> 
    <td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $trait->getColor(); ?>"></div></td>
    <td class="text-left" colspan="3"><?php echo $trait->getColor(); ?></td>
</tr>
<?php } } ?>
<?php if(sizeof($carried) > 0) { ?>
<tr><th colspan="5" class="content-subheader">carried traits</th></tr>
<?php foreach($carried as $trait) { ?>
<tr<?php $cur = row($cur); ?>>
    <td class="content-miniheader width150"><?php echo $trait->getTitle(); ?></td>        
    <td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $trait->getColor(); ?>"></div></td>
    <td class="text-left" colspan="3"><?php echo $trait->getColor(); ?></td>
</tr>
<?php } } ?>

<?php 
$num = $squffy->getNumItems();
if($num > 0) { 
	$squffy->fetchItems();
	$items = $squffy->getItems();
	echo '<tr><th colspan="5" class="content-subheader">current outfit</th></tr>';
	//echo '<tr><td class="width150">ITEM</td><td class="width150" colspan="2">ITEM</td><td class="width150">ITEM</td><td class="width150">ITEM</td></tr>';
	
	for($i = 0; $i < $num; $i++) {
		$item = $items[$i];
		if($i % 4 == 0) { echo '<tr>'; }
		echo '<td class="width150">
		<img class="item" src="./images/items/' . str_replace(" ", "", $item['item_name']) . '.png">
		<br />' . $item['item_name'] . '</td>';
		if($i % 4 == 3) { echo '</tr>'; }
	}
	while($i % 4 != 0) { echo '<td></td>'; $i++; }
	echo '</tr>';
} ?>
</table>

</td>
</tr>
</table>

<?php
function row($cur) {
	echo ' class="' . $cur . '"';
	return $cur == "odd" ? "even" : "odd";
}

include('./includes/footer.php');
?>