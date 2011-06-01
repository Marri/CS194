<?php
class Item{
	//type 1 is a nut (food)
	//type 2 is squffy dollars
	//type 3 makes customs
	//type 4 are accessories
	//type 5 are backgrounds
	//type 6 is non-nut non-candy food
	//type 7 is a candy food
	//type 8 is an ingredient: wood, metal
	//type 9 is a toy
	//type 10 is a farm tool
	
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
	public function getImage() { return './images/items/' . strtolower(str_replace(" ","",$this->name)) . '.png'; }
	
	public function canMakeCustom() { return $this->type == 3; }
	public function isFood() { return $this->type == 1; }
	public function isClothing() { return $this->type == 4; }
	public function isBackground() { return $this->type == 5; }
		
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
	
	public static function getItemByID($id) {
		$queryString = "SELECT * FROM items WHERE item_id='".$id."'";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		$items = @mysql_fetch_assoc($query);
		$item = new Item();
		$item->id = $items['item_id'];
		$item->column_name = self::convertItemName($items['item_name']);
		$item->name = $items['item_name'];
		$item->type = $items['item_type'];
		$item->description = $items['item_description'];
		if(!$item->isFood()) { return $item; }
		$food = Food::makeFood($items);
		return $food;
	}
		
	public static function getItemNameFromID($item_id){
		$item = self::getItemByID($item_id);
		if($item == NULL) { return NULL; }
		return $item->getName();
	}
}
?>
