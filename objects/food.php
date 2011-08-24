<?php
class Food extends Item {
	private
		$chromosome,
		$c_increase,
		$h_decrease;
		
	public static function makeFood($info) {
		$food = new Food();
		$food->chromosome = $info['chromosome'];
		$food->c_increase = $info['c_increase'];
		$food->h_decrease = $info['h_decrease'];
		$food->id = $info['item_id'];
		$food->column_name = Item::convertItemName($info['item_name']);
		$food->name = $info['item_name'];
		$food->type = $info['item_type'];
		$food->description = $info['item_description'];
		return $food;
	}
		
	public function affectsChromosomes() { return $this->chromosome != NULL; }
		
	public function getChromosome() { return $this->chromosome; }
	public function getID() { return $this->id; }
	public function getName(){ return $this->name; }
	public function getColumnName(){ return $this->column_name; }
	public function getDescription(){ return $this->description; }
	public function getChromosomeIncrease() { return $this->c_increase; }	
	public function getHungerDecrease() { return $this->h_decrease; }
	
	public function isFood() { return true; }
}
?>
