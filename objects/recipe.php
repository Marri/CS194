<?php
class Recipe {
	private
		$recipe_id,
		$recipe_name,
		$ing1,
		$ing1_name,
		$ing1_amount,
		$ing2,
		$ing2n,
		$ing2_amount,
		$ing3,
		$ing3n,
		$ing3_amount,
		$ing4,
		$ing4n,
		$ing4_amount,
		$makes,
		$makesn,
		$num_ing;
		
	public function __construct($info) {
		foreach($info as $key => $var) {
			$this->$key = $var;
		}
		
		for($i = 1; $i < 5; $i++) {
			$name = 'ing' . $i;
			if($this->$name == NULL) {
				$this->num_ing = $i - 1;
				break;
			}
		}
	}
	
	public function getName() { return $this->recipe_name; }
	public function getIngredients() {
		$ings = array();
		for($i = 1; $i <= $this->num_ing; $i++) {
			$info = array();
			$name = 'ing' . $i;
			$info['id'] = $this->$name;
			$am = $name . '_amount';
			$info['amount'] = $this->$am;
			$name = $name . 'n';
			$info['name'] = $this->$name;
			$ings[] = $info;
		}
		return $ings;
	}
	
	public function fetchNames() {
		$in = '';
		for($i = 1; $i <= $this->num_ing; $i++) {
			$name = 'ing' . $i;
			$in .= ', ' . $this->$name;
		}
		$in .= ', ' . $this->makes;
		
		$query = 'SELECT `item_id`, `item_name` FROM `items` WHERE `item_id` IN (' . substr($in, 2) . ')';
		$result = runDBQuery($query);
		$names = array();
		while($info = @mysql_fetch_assoc($result)) {
			$names[$info['item_id']] = $info['item_name'];
		}
				
		for($i = 1; $i <= $this->num_ing; $i++) {
			$name = 'ing' . $i . 'n';
			$ing = 'ing' . $i;			
			$this->$name = $names[$this->$ing];
		}
		$this->makesn = $names[$this->makes];
	}
}
?>