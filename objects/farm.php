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
		$state,
		$is_fertilized,
		$num_workers;

	public function __construct($info) {
		$this->id = $info['farm_id'];
		$this->name = $info['farm_name'];
		$this->user_id = $info['user_id'];
		$this->type = $info['farm_type'];
		$this->state = $info['state'];
		$this->is_fertilized = $info['is_fertilized'];
		$this->num_workers = $info['num_workers'];
	}

	public function getName() { return $this->name; }
	public function getDisplayType() {
		switch($this->type) {
			case self::FARM: return "Farm";
			case self::ORCHARD: return "Orchard";
			case self::GARDEN: return "Garden";
			default: return "Unknown";
		}
	}
	
	public function getWorkers() {
		if($this->num_workers < 1) { return array(); }
		
		$query = "SELECT farmer_id FROM jobs_farming WHERE farm_id = " . $this->id;
		$result = runDBQuery($query);
		
		$ids = '';
		while($info = @mysql_fetch_assoc($result)) {
			$ids .= ', ' . $info['farmer_id'];
		}
		$ids = substr($ids, 2);
		
		$query = "SELECT * FROM squffies WHERE squffy_id IN ($ids)";
		return Squffy::getSquffies($query);
	}
	
	public function setState($state) {
		$this->state = $state;
		$query = "UPDATE farms SET state = $state WHERE farm_id = " . $this->id;
		runDBQuery($query);
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
}
?>