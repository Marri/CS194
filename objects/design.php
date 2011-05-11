<?php
class Design {	
	private
		$id,
		$name,
		$user,
		$base,
		$eye,
		$foot,
		$species,
		$species_name,
		$num_traits,
		$traits;		
		
	public function __construct($info) {
		$this->id = $info['design_id'];
		$this->name = $info['design_name'];
		$this->user = $info['user_id'];
		$this->base = $info['base_color'];
		$this->eye = $info['eye_color'];
		$this->foot = $info['foot_color'];
		$this->species = $info['species_id'];
		$this->num_traits = $info['num_traits'];
		$this->traits = NULL;
		if($info['num_traits'] < 1) { $this->traits = array(); }
		$this->species_name = NULL;
	}
	
	public function getID() { return $this->id; }
	public function getName() { return $this->name; }
	public function getSpecies() { return $this->species; }
	public function getSpeciesName() { return $this->species_name; }
	public function getTraits() { return $this->traits; }
	public function getBase(){ return $this->base; }
	public function getEye(){ return $this->eye; }
	public function getFoot(){ return $this->foot; }
	public function getUser(){ return $this->user; }
	public function getNumTraits() { return $this->num_traits; }
	
	public static function GetUserDesigns($userid) {
		$query = "SELECT * FROM designs WHERE user_id = $userid";
		$result = runDBQuery($query);
		$designs = array();
		while($info = @mysql_fetch_assoc($result)) {
			$designs[] = new Design($info);
		}
		return $designs;
	}
	
	public static function GetDesign($id) {
		$query = "SELECT * FROM designs WHERE design_id = $id";
		$result = runDBQuery($query);
		$info = @mysql_fetch_assoc($result);
		$design = new Design($info);
		return $design;
	}
	
	public function fetchTraits() {
		if($this->traits != NULL) { return; }
		$query = "
		SELECT design_traits.trait_color, appearance_traits.trait_name, appearance_traits.trait_title, appearance_traits.trait_type
		FROM design_traits, appearance_traits 
		WHERE design_traits.trait_id = appearance_traits.trait_id AND design_id = " . $this->id . "
		ORDER BY trait_order DESC";
		$result = runDBQuery($query);
		
		$this->traits =array();
		while($info = @mysql_fetch_assoc($result)) {
			$trait = array();
			$trait['name'] = $info['trait_name'];
			$trait['title'] = $info['trait_title'];
			$trait['color'] = $info['trait_color'];
			$trait['type'] = $info['trait_type'];
			$this->traits[] = $trait;
		}
	}
	
	public function fetchSpecies() {
		if($this->species_name != NULL) { return; }
		$query = "SELECT species_name FROM species WHERE species_id = " . $this->species;
		$result = runDBQuery($query);
		$info = @mysql_fetch_assoc($result);
		$this->species_name = $info['species_name'];
	}
}
?>