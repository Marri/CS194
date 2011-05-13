<?php
$selected = "home";
include("./includes/header.php");



$lot_name_error = "";
$sell_id_error = "";
$sell_amount_error = "";
$want_id_error = "";
$want_amount_error = "";
$auction_ends_error = "";
$can_sell_error = "";
$can_buy_error = "";

$lot_name = "";
$sell_id = "";
$sell_amount = "";
$want_id = "";
$want_amount = "";
$auction_ends = "";
$buy_id = "";
$buy_amount = "";


if($loggedin){
if(isset($_POST['buyItem'])){
	$lot_id = mysql_real_escape_string($_POST['lot_id']);
	$sale_id = mysql_real_escape_string($_POST['sale_id']);
	$sale_name = Item::GetItemNameFromID($sale_id);
	$sale_amount = mysql_real_escape_string($_POST['sale_amount']);
	$want_id = mysql_real_escape_string($_POST['want_id']);
	$want_name = Item::GetItemNameFromID($want_id);
	$want_amount = mysql_real_escape_string($_POST['want_amount']);
	$seller_id = mysql_real_escape_string($_POST['seller_id']);
	$can_buy_error = $user->buyItem($lot_id, $sale_id, $sale_name, $sale_amount, $want_id, $want_name, $want_amount, $seller_id);
}
if(isset($_POST['newLot'])){
	$lot_name = mysql_real_escape_string($_POST['lot_name']);
	$sell_id = mysql_real_escape_string($_POST['sell_id']);
	$sell_amount = mysql_real_escape_string($_POST['sell_amount']);
	$want_id = mysql_real_escape_string($_POST['want_id']);
	$want_amount = mysql_real_escape_string($_POST['want_amount']);
	$auction_ends = $_POST['auction_ends'];
	$lot_type =  mysql_real_escape_string($_POST['lot_type']);
	
	$selling_squffy = false;
	if(isset($_POST['selling_squffy'])){
		$selling_squffy = true;
	}
	
	$canCreateLot = true;
	if($lot_name == ""){
		$canCreateLot = false;
		$lot_name_error = "Can't use empty string for lot name.";
	}
	
	$sell_name = Item::GetItemNameFromID($sell_id);
	if($sell_name == NULL){
		$canCreateLot = false;
		$sell_id_error = "Invalid ID for the Item you're selling";
	}
	if($sell_amount <= 0){
		$canCreateLot = false;
		$sell_amount_error = "Invalid Amount";
	}
	
	$want_name = Item::GetItemNameFromID($want_id);
	if($want_name == NULL){
		$canCreateLot = false;
		$want_id_error = "Invalid ID for the Item you want";
	}
	if($want_amount <= 0){
		$canCreateLot = false;
		$want_amount_error = "Invalid Amount";
	}
	
	if($auction_ends != ""){
		$token = strtok($auction_ends, "/");
		$auction_format = array(); //0 => month, 1 => day, 2 => year
		while ($token != false){
			array_push($auction_format, $token);
			$token = strtok("/");
		}
		if((count($auction_format) != 3) || !(checkdate($auction_format[0], $auction_format[1], $auction_format[2]))){
			$canCreateLot = false;
			$auction_ends_error = "Wrong date format.";
		}			
	}else{
		$canCreateLot = false;
		$auction_ends_error = "Empty String invalid";
	}
	if($canCreateLot){
		if($user->canSellItem($sell_name, $sell_amount)){
			if($selling_squffy){
				//set up squffy lot
			}else{
				Lot::CreateSellItemLot($lot_name, $userid, $sell_id, $sell_amount, $want_id, $want_amount, $lot_type, $auction_ends, $selling_squffy);
			}
		}else{
			$can_sell_error = "Can't Sell Lot. Not enough of sale item.";
		}
	}
}

$user_lots = Lot::GetUserLots($userid);
$other_lots = Lot::GetOtherLots($userid);

?>

<div class='text-center width100p'><h1>Marketplace</h1></div>
<p><?php echo $can_buy_error; ?></p>

<h2>Your Market Stall</h2>
<table>
	<tr>
		<th>Lot ID</th>
		<th>Lot Name</th>
		<th>Item For Sale</th>
		<th>Selling Price</th>
		<th>Finished</th>
	</tr>
<?php
for($i = 0; $i < count($user_lots); $i++){	?>
	
	<tr>
		<td><?php echo $user_lots[$i]->getID(); ?> </td>
		<td><?php echo $user_lots[$i]->getName(); ?> </td>
		<td><?php echo $user_lots[$i]->getSaleAmount()." ".Item::getItemNameFromID($user_lots[$i]->getSaleItemID()) ?></td>
		<td><?php echo $user_lots[$i]->getWantedItemAmount()." ".Item::getItemNameFromID($user_lots[$i]->getWantedItemId())."(s)"; ?></td>
		<td><?php echo $user_lots[$i]->isFinished(); ?></td>
	</tr>
	<?php } ?>
</table>

<h2>Other Market Stalls</h2>
<table>
	<tr>
		<th>Lot ID</th>
		<th>User Id</th>
		<th>Lot Name</th>
		<th>Item For Sale</th>
		<th>Selling Price</th>
	</tr>
<?php
for($i = 0; $i < count($other_lots); $i++){	?>
	
	<tr>
		<td><?php echo $other_lots[$i]->getID(); ?> </td>
		<td><?php echo $other_lots[$i]->getUserID(); ?> </td>
		<td><?php echo $other_lots[$i]->getName(); ?></td>
		<td><?php echo Item::getItemNameFromID($other_lots[$i]->getSaleItemID()) ?></td>
		<td><?php echo $other_lots[$i]->getWantedItemAmount()." ".Item::getItemNameFromID($other_lots[$i]->getWantedItemId())."(s)"; ?></td>
		<td>
			<form action="" method="post">
				<input type="submit" value="Buy!" name="buyItem" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetID(); ?>" name="lot_id" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetSaleItemID(); ?>" name="sale_id" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetSaleAmount(); ?>" name="sale_amount" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetWantedItemID(); ?>" name="want_id" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetWantedItemAmount(); ?>" name="want_amount" />
				<input type="hidden" value="<?php echo $other_lots[$i]->GetUserID(); ?>" name="seller_id" />
			</form>
		</td>
	</tr>
	<?php } ?>
</table>


<br><h2> Create Lot </h2></br>
<p><?php echo $can_sell_error; ?></p>
<form action="" method="post">
<br><label>Lot Name: </label><input type="text" name="lot_name" value="<?php echo $lot_name; ?>"/> <?php echo $lot_name_error; ?></br>
<br><input type="checkbox" name="selling_squffy" value="true"/> selling squffy</br>
<br><label>ID of Squffy or Item you're selling: </label><input type="text" name="sell_id" value="<?php echo $sell_id; ?>"/><?php echo $sell_id_error; ?></br>
<br><label>How many you're selling: </label><input type="text" name="sell_amount" value="<?php echo $sell_amount; ?>"/><?php echo $sell_amount_error; ?></br>
<br><label>What item you want for it: </label><input type="text" name="want_id" value="<?php echo $want_id; ?>"/><?php echo $want_id_error; ?></br>
<br><label>How many units of that item your want: </label><input type="text" name="want_amount" value="<?php echo $want_amount; ?>"/> <?php echo $want_amount_error; ?></br>
<br><label>Lot Type:</label></br><br><input type="radio" name="lot_type" value="sale" checked="true" /> Sale</br>
<!-- <br><input type="radio" name="lot_type" value="trade"/> Trade</br>
<br><input type="radio" name="lot_type" value="auction"/> Auction</br>
<br><label>Sale Ends: </label><input type="text" name="auction_ends" value="<?php echo $auction_ends; ?>"/> Please enter in mm/dd/yyyy format  <?php echo $auction_ends_error; ?></br> -->
<br><input type="submit" name="newLot" value="Create Lot"/></br>
</form>


<?php
}
include('./includes/footer.php');
?>