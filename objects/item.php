<?php
class Item{
	const CUSTOM_TYPE = 3;

	private
		$id,
		$name,
		$column_name,
		$type,
		$description;
		
	public function getName(){ return $this->name; }
	public function getColumnName(){ return $this->column_name; }
	public function getDescription(){ return $this->description; }

	public function canMakeCustom() { return $this->type == self::CUSTOM_TYPE; }
	
	public static function getItemList(){
		$item_list = array();
		
		$queryString = "SELECT * FROM items;";
		$results = runDBQuery($queryString);
		while($items = mysql_fetch_assoc($results)) {
			$item = new Item();
			$item->id = $items['item_id'];
			$item->column_name = str_replace(" ", "_", strtolower($items['item_name']));
			$item->name = $items['item_name'];
			$item->type = $items['item_type'];
			$item->description = $items['item_description'];
			array_push($item_list, $item);
		}
		return $item_list;
	}
	public static function getItemNameFromID($item_id){
		$queryString = "SELECT item_name FROM items WHERE item_id='".$item_id."'";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		$item_names = mysql_fetch_assoc($query);
		return $item_names['item_name'];
	}
}
?>
