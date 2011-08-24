<?php
class Farm {
	const FARM = 1;
	const ORCHARD = 2;
	const GARDEN = 3;
	
	const CLEAR = 0;
	const PLOWED = 1;
	const GROWING = 2;
	const DEAD = 3;
	const HARVEST = 4;

	//states: empty, plowed, growing, dead, grown
	//options: plow, fertilize, water, weed, harvest, clear

	private
		$id,
		$name,
		$user_id,
		$type,
		$food_id,
		$state,
		$is_fertilized,
		$num_crops,
		$weeds,
		$dryness,
		$date_ripe,
		$num_workers;

	public function __construct($info) {
		$this->id = $info['farm_id'];
		$this->name = $info['farm_name'];
		$this->user_id = $info['user_id'];
		$this->type = $info['farm_type'];
		$this->date_ripe = $info['date_ripe'];
		$this->food_id = $info['food_id'];
		$this->state = $info['cur_state'];
		$this->dryness = $info['dryness'];
		$this->weeds = $info['weeds'];
		$this->num_crops = $info['num_crops'];
		$this->is_fertilized = $info['is_fertilized'];
		$this->num_workers = $info['num_workers'];
	}

	public function getName() { return $this->name; }
	public function getOwner() { return $this->user_id; }
	public function getDateRipe() { return $this->date_ripe; }
	public function getWeeds() { return $this->weeds; }
	public function getDryness() { return $this->dryness; }
	public function getNumCrops() { return $this->num_crops; }
	public function getNumWorkers() { return $this->num_workers; }
	public function getState() { return $this->state; }
	public function getFoodID() { return $this->food_id; }
	public function isFertilized() { return $this->is_fertilized == 'true'; }
	public function getLink() { return '<a href="farm.php?id=' . $this->id . '">' . $this->name . '</a>'; }
	public function getDisplayType() {
		switch($this->type) {
			case self::FARM: return "Farm";
			case self::ORCHARD: return "Orchard";
			case self::GARDEN: return "Garden";
			default: return "Unknown";
		}
	}
	public function getDisplayState() { 
		switch($this->state) {
			default: return $this->state;
		}
	}
	
	public function getWorkers() {
		if($this->num_workers < 1) { return array(); }
		
		$query = "SELECT farmer_id, chore_type, date_finished FROM jobs_farming WHERE farm_id = " . $this->id;
		$result = runDBQuery($query);
		
		$ids = '';
		$response = array();
		while($info = @mysql_fetch_assoc($result)) {
			$ids .= ', ' . $info['farmer_id'];
			$response['chore'] = $info['chore_type'];
			$response['done'] = $info['date_finished'];
		}
		$ids = substr($ids, 2);
		
		$query = "SELECT * FROM squffies WHERE squffy_id IN ($ids)";
		$squffies = Squffy::getSquffies($query);
		$response['squffies'] = $squffies;
		return $response;
	}
	
	public function setState($state) {
		$this->state = $state;
		$query = "UPDATE farms SET state = $state WHERE farm_id = " . $this->id;
		runDBQuery($query);
	}
	
	public static function GetFarmByID($id) {
		$query = 'SELECT * FROM farms WHERE farm_id = ' . $id;
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		return (new Farm($info));
	}

	public static function GetFarmsByUser($userid) {
		$query = 'SELECT * FROM farms WHERE user_id = ' . $userid;
		$result = runDBQuery($query);
		$farms = array();
		if(@mysql_num_rows($result) < 1) { return $farms; }
		while($info = @mysql_fetch_assoc($result)) {
			$farms[] = new Farm($info);
		}
		return $farms;
	}
	
	public static function CreateFarm($name, $type, $user) {
		$query = "INSERT INTO farms (farm_name, farm_type, user_id, food_id, cur_state, dryness, weeds)
		VALUES ('$name', $type, $user, NULL, 'Empty', NULL, NULL)";
		runDBQuery($query);
		$id = @mysql_insert_id();
		return $id;
	}
}
?>