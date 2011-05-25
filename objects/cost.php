<?php
class Cost {
	private
		$sdPrice,
		$itemID,
		$itemName,
		$colName,
		$itemPrice;
		
	public function __construct($info) {
		$this->sdPrice = $info['sd'];
		$this->itemID = $info['id'];
		$this->itemPrice = $info['amount'];
		$this->itemName = NULL;
	}
		
	public function getSDPrice() { return $this->sdPrice; }
	public function getItemID() { return $this->itemID; }
	public function getItemName() {
		$this->fetchName(); 
		return $this->itemName; 
	}
	public function getColumnName() {
		$this->fetchName(); 
		return $this->colName; 
	}
	public function getItemPrice() { return $this->itemPrice; }
	
	public function fetchName() {
		if($this->itemName != NULL) { return; }
		$query = "SELECT item_name FROM items WHERE item_id = " . $this->itemID;
		$result = runDBQuery($query);
		$info = @mysql_fetch_assoc($result);
		$this->itemName = $info['item_name'];
		$this->colName = strtolower(str_replace(" ", "_", $info['item_name']));
	}
}
?>