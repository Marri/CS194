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
		elseif($breed == "seed") { $species = 3; }
		elseif($breed == "horn") { $species = 2; }
		elseif($breed == "ings") { $species = 5; }
		
		$info['species'] = $species;
		return $info;
	}
}
?>
