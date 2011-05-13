<?php

class Lot{
	private
		$id,
		$name,
		$user_id,
		$type,
		$is_finished,
		$selling_squffy,
		$sale_price_squffy_id,
		$sale_price_selling_item_id,
		$sale_price_selling_amount,
		$sale_price_wanted_item_id,
		$sale_price_wanted_amount,
		$auction_ends,
		$auction_item_id;
	public function __construct(){
		$this->selling_squffy = false;
	}
	
	public function isSellingSquffy(){ return $this->selling_squffy; }	
	public function isAuction(){
		if($this->auction_item_id > 0) return true;
		return false;
	}
	public function isFinished(){ return $this->is_finished; }
	
	public function getId(){ return $this->id; }
	public function getName(){ return $this->name; }
	public function getLotType(){ return $this->type; }
	public function getSaleAmount(){ return $this->sale_price_selling_amount; }
	public function getSaleItemID(){ return $this->sale_price_selling_item_id; }
	public function getWantedItemID(){ return $this->sale_price_wanted_item_id; }
	public function getWantedItemAmount(){ return $this->sale_price_wanted_amount; }
	public function getAuctionEndDate(){ return $this->auction_ends; }
	public function getAuctionItemID(){ return $this->auction_item_id; }
	public function getUserID(){ return $this->user_id; }
	
	public static function GetUserLots($user_id){
		$user_lots = array();
		$queryString = "SELECT * FROM lots WHERE user_id='".$user_id."'";
		$results = runDBQuery($queryString);
		
		while($lots = mysql_fetch_assoc($results)){
			$curr_lot = new Lot();
			$curr_lot->id = $lots['lot_id'];
			$curr_lot->name = $lots['lot_name'];
			$curr_lot->user_id = $user_id;
			$curr_lot->type = $lots['lot_type'];
			$curr_lot->is_finished = $lots['is_finished'];
			$curr_lot->sale_price_squffy_id = $lots['sale_price_squffy_id'];
			if($curr_lot->sale_price_squffy_id > 0) $curr_lot->selling_squffy = true;
			$curr_lot->sale_price_selling_item_id = $lots['sale_price_selling_item_id'];
			$curr_lot->sale_price_selling_amount = $lots['sale_price_selling_amount'];
			$curr_lot->sale_price_wanted_item_id = $lots['sale_price_wanted_item_id'];
			$curr_lot->sale_price_wanted_amount = $lots['sale_price_wanted_amount'];
			$curr_lot->auction_ends = $lots['auction_ends'];
			$curr_lot->auction_item_id = $lots['auction_item_id'];
			array_push($user_lots, $curr_lot);
		}
		return $user_lots;
	}
	public static function getOtherLots($user_id){
		$other_lots = array();
		$queryString = "SELECT * FROM lots WHERE user_id != '".$user_id."' AND is_finished='false'";
		$results = runDBQuery($queryString);
		
		while($lots = mysql_fetch_assoc($results)){
			$curr_lot = new Lot();
			$curr_lot->id = $lots['lot_id'];
			$curr_lot->name = $lots['lot_name'];
			$curr_lot->user_id = $lots['user_id'];
			$curr_lot->type = $lots['lot_type'];
			$curr_lot->is_finished = $lots['is_finished'];
			$curr_lot->sale_price_squffy_id = $lots['sale_price_squffy_id'];
			if($curr_lot->sale_price_squffy_id > 0) $curr_lot->selling_squffy = true;
			$curr_lot->sale_price_selling_item_id = $lots['sale_price_selling_item_id'];
			$curr_lot->sale_price_selling_amount = $lots['sale_price_selling_amount'];
			$curr_lot->sale_price_wanted_item_id = $lots['sale_price_wanted_item_id'];
			$curr_lot->sale_price_wanted_amount = $lots['sale_price_wanted_amount'];
			$curr_lot->auction_ends = $lots['auction_ends'];
			$curr_lot->auction_item_id = $lots['auction_item_id'];
			array_push($other_lots, $curr_lot);
		}
		return $other_lots;
	}
	public static function CreateSellItemLot($lot_name, $userid, $sell_id, $sell_amount, $want_id, $want_amount, $lot_type, $auction_ends){	
		$queryString = "INSERT INTO lots (lot_name, user_id, lot_type, sale_price_selling_item_id, sale_price_selling_amount, sale_price_wanted_item_id, sale_price_wanted_amount, auction_ends) VALUES ('".$lot_name."', '".$userid."', '".$lot_type."', '".$sell_id."', '".$sell_amount."', '".$want_id."', '".$want_amount."', '".$auction_ends."');";
		//echo $queryString;
		runDBQuery($queryString);
	}
	public static function FinishLot($lot_id){
		$queryString = "UPDATE lots SET is_finished='true' WHERE lot_id='".$lot_id."'";
		runDBQuery($queryString);
	}
	public static function LotFinished($lot_id){
		$queryString = "SELECT is_finished FROM lots WHERE lot_id='".$lot_id."'";
		$query = runDBQuery($queryString);
		$lot = mysql_fetch_assoc($query);
		return $lot['is_finished'];
	}
}

?>
