<?php
$selected = "home";
include("./includes/header.php");

$item_list = Item::getItemList();
$inventory = $user->getInventory();
?>

<div class='text-center width100p'><h1>Item Hoard</h1></div>


<div class='text-center width100p'>
<table><tr> <th>Item</th><th>Description</th><th>Item Number</th> </tr>
<?php
	for($i = 0; $i < count($item_list); $i++){
		$curr_item = $item_list[$i]; ?>
		
		<tr>
			<td><?php echo $curr_item->getName() ?></td>
			<td><?php echo $curr_item->getDescription() ?></td>
			<td><?php 
				if(isset($inventory[$curr_item->getColumnName()])){
					echo  $inventory[$curr_item->getColumnName()];
				}else{
					echo 0;
				}?>
			</td></tr>
		
		<?php
	}
?>
</table>	
</div>

<?php
include('./includes/footer.php');
?>