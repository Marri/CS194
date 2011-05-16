<?php
class Item{
	private
		$id,
		$name,
		$column_name,
		$type,
		$description;
		
	public function getID() { return $this->id; }
	public function getName(){ return $this->name; }
	public function getColumnName(){ return $this->column_name; }
	public function getDescription(){ return $this->description; }
	public function canMakeCustom() {
		$breed = substr($this->name, -5);
		$species = 0;
		if($breed == "Acorn") { $species = 1; }
		elseif($breed == "Spark") { $species = 4; }
		elseif($breed == " Seed") { $species = 2; }
		elseif($breed == "Thorn") { $species = 3; }
		if($species > 0) { return true; }
		return false;
	}

		
	public static function getItemList(){
		$item_list = array();
		
		$queryString = "SELECT * FROM items;";
		$results = runDBQuery($queryString);
		while($items = mysql_fetch_assoc($results)) {
			$item = new Item();
			$item->id = $items['item_id'];
			$item->column_name = self::convertItemName($items['item_name']);
			$item->name = $items['item_name'];
			$item->type = $items['item_type'];
			$item->description = $items['item_description'];
			array_push($item_list, $item);
		}
		return $item_list;
	}

	public static function convertItemName($item_name){
		return str_ireplace(" ", "_", strtolower($item_name));
	}

	
	public static function CustomInfo($item) {
		$info = array();
	
		$type = substr($item, 0, 6);
		$canUse = 0;
		
		if($type == "single") { $canUse = 1; }
		elseif($type == "double") { $canUse = 2; }
		elseif($type == "triple") { $canUse = 3; }
		
		$info['num'] = $canUse;
		
		$species = "tree";
		$breed = substr($item, -4);
		if($breed == "corn") { $species = 1; }
		elseif($breed == "park") { $species = 4; }
		elseif($breed == "seed") { $species = 2; }
		elseif($breed == "horn") { $species = 3; }
		elseif($breed == "ings") { $species = 5; }
		
		$info['species'] = $species;
		return $info;
	}	
	public static function getItemNameFromID($item_id){
		$queryString = "SELECT item_name FROM items WHERE item_id='".$item_id."'";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		$item_names = @mysql_fetch_assoc($query);
		return $item_names['item_name'];
	}
}
?>
