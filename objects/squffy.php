<?php

class Squffy {
	const SICK = 50;
	const HUNGRY = 50;
	const DAYS_PREG = 5;
	const ADULT = 20;
	const TEEN = 15;
	
	const FETCH_APPEARANCE = 1;
	const FETCH_FULL_APPEARANCE = 6;
	const FETCH_PERSONALITY = 2;
	const FETCH_ITEMS = 3;
	const FETCH_DEGREE = 4;
	const FETCH_FAMILY = 5;
	const FETCH_SPECIES = 7;
	
	const HEALING_TRAIT = 6;
  
	private
		$id,
		$owner_id,
		$name,
		$gender,
		$birthday,
		$age,
		$is_pregnant,
		$is_custom,
		$is_in_market, //for sale, trade or auction
		$is_breedable, //available for non-owner breeding
		$is_hireable, //available for non-owners to hire
		$is_working, //currently working on a job
		$appearance_traits, //More info required from other tables
		$personality_traits,
		$family_tree, //More info required from other tables		
		$species_id,
		$species,
		$degree_id,
		$degree_type,
		$degree_name,
		$mate_id,		
		$hunger,
		$health,
		$energy,
		$happiness,
		$luck,
		$items,
		
		$c1,
		$c2,
		$c3,
		$c4,
		$c5,
		$c6,
		$c7,
		$c8,
		$base_color,
		$eye_color,
		$foot_color,
		$breeding_rights, //user id of user with breeding rights
		$rights_revert, //date and time breeding rights revert to owner
		$num_items,		
		$breed_price, //what it costs for non-owners to breed
		$hire_price; //what it costs for non-owners to hire		
		
	//Constructors
	public function __construct($info) {
		//Fill in all data from the database result
		$this->id = $info['squffy_id'];
		$this->owner_id = $info['squffy_owner'];		
		$this->name = $info['squffy_name'];
		$this->gender = $info['squffy_gender'];
		$this->birthday = $info['squffy_birthday'];
		$this->is_pregnant = $info['is_pregnant'];
		$this->is_custom = $info['is_custom'];
		$this->is_breedable = $info['is_breedable'];
		$this->is_hireable = $info['is_hireable'];
		$this->is_working = $info['is_working'];
		$this->is_in_market = $info['is_in_market'];		
		$this->species_id = $info['squffy_species'];		
		$this->degree_type = $info['degree_type'];
		$this->degree_id = $info['squffy_degree'];
		$this->mate_id = $info['mate_id'];
		$this->hunger = $info['hunger'];
		$this->health = $info['health'];
		$this->energy = $info['energy'];
		$this->happiness = $info['happiness'];
		$this->luck = $info['luck'];
		
		//Defaults for separately fetched information
		$this->appearance_traits = NULL;
		$this->items = NULL;
		$this->personality_traits = array();
		$this->personality_traits['strength1'] = $info['strength1_id'];
		$this->personality_traits['strength2'] = $info['strength2_id'];
		$this->personality_traits['weakness1'] = $info['weakness1_id'];
		$this->personality_traits['weakness2'] = $info['weakness2_id'];
		
		//Optionally included information
		if(array_key_exists('mother_id', $info)) {
			$this->family_tree = array();
			$this->family_tree['mother'] = $info['mother_id'];
			$this->family_tree['father'] = $info['father_id'];
			$this->family_tree['mother_mother'] = $info['mother_mother_id'];
			$this->family_tree['mother_father'] = $info['mother_father_id'];
			$this->family_tree['father_mother'] = $info['father_mother_id'];
			$this->family_tree['father_father'] = $info['father_father_id'];
		}
		
		if(array_key_exists('species_name', $info)) {
			$this->species = $info['species_name'];
		}
		
		if(array_key_exists('degree_name', $info)) {
			$this->degree_name = $info['degree_name'];
		}
		
		//Calculate age
		$this->age = floor((time() - strtotime($this->birthday)) / (24 * 3600));
		if($this->isCustom()) { $this->age += 20; }
	}
	
	public static function getSquffies($query) {
		$result = runDBQuery($query);
		$results = array();
		while($info = @mysql_fetch_assoc($result)) {
			$results[] = new Squffy($info);
		}
		return $results;
	}
	
	public static function getSquffyByID($id) {
		$queryString = 'SELECT * FROM `squffies` WHERE squffies.`squffy_id` = ' . $id;
		$result = runDBQuery($queryString);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		return new Squffy($info);
	}
	
	public static function getSquffyByIDExtended($id, array $options) {
		$queryString = 'SELECT *, squffies.squffy_id as squffy_id FROM `squffies`';
		if(in_array(self::FETCH_FAMILY, $options)) { $queryString .= ' LEFT JOIN squffy_family ON squffy_family.squffy_id = ' . $id; }
		if(in_array(self::FETCH_SPECIES, $options)) { $queryString .= ' JOIN species ON species.species_id = squffies.squffy_species'; }
		if(in_array(self::FETCH_DEGREE, $options)) { $queryString .= ' LEFT JOIN degrees ON degrees.degree_id = squffies.squffy_degree'; }
		$queryString .= ' WHERE squffies.`squffy_id` = ' . $id;
		$result = runDBQuery($queryString);
		$info = @mysql_fetch_assoc($result);
		$squffy = new Squffy($info);
		
		if(in_array(self::FETCH_FULL_APPEARANCE, $options)) { $squffy->fetchFullAppearance(); }
		else if(in_array(self::FETCH_APPEARANCE, $options)) { $squffy->fetchAppearance(); }
		if(in_array(self::FETCH_PERSONALITY, $options)) { $squffy->fetchPersonality(); }
		if(in_array(self::FETCH_ITEMS, $options)) { $squffy->fetchItems(); }
		return $squffy;
	}
		
	//Getters
	public function getID() { return $this->id; }
	public function getName() { return $this->name; }
	public function getGender() { return $this->gender; }
	public function getAge() { return $this->age; }
	public function getBirthday() { return $this->birthday; }
	public function getSpeciesID() { return $this->species_id; }
	public function getSpecies() { return $this->species; }
	public function getHunger() { return $this->hunger; }
	public function getHealth() { return $this->health; }
	public function getEnergy() { return $this->energy; }
	public function getHappiness() { return $this->happiness; }
	public function getLuck() { return $this->luck; }
	public function getDegreeName() { return $this->degree_name; }
	public function getDegreeType() { return $this->degree_type; }
	public function getOwnerID() { return $this->owner_id; }
	public function getMateID() { return $this->mate_id; }
	public function getAppearanceTraits() { return $this->appearance_traits; }
	public function getPersonalityTraits() { return $this->personality_traits; }
	public function getItems() { return $this->items; }
	public function getMotherID() { return $this->family_tree['mother']; }
	public function getFatherID() { return $this->family_tree['father']; }
	public function getMotherMotherID() { return $this->family_tree['mother']; }
	public function getMotherFatherID() { return $this->family_tree['father']; }
	public function getFatherMotherID() { return $this->family_tree['father_mother']; }
	public function getFatherFatherID() { return $this->family_tree['father_father']; }
	
	//Predicates
	public function isPregnant() { return $this->is_pregnant == "true"; }
	public function isSick() { return $this->health < self::SICK; }
	public function isHungry() { return $this->hunger > self::HUNGRY; }
	public function isWorking() { return $this->is_working == "true"; }
	public function isHireable() { return $this->is_hireable == "true"; }
	public function isBreedable() { return $this->is_breedable == "true"; }	
	public function isCustom() { return $this->is_custom == "true"; }
	public function isInMarket() { return $this->is_in_market == "true"; }
	public function isStudent() { return $this->getDegreeType() == 'Apprentice'; }
	public function isAdult() { return $this->getAge() >= self::ADULT; }
	public function isTeenager() { return $this->getAge() >= self::TEEN && !$this->isAdult(); }
	
	public function hasMate() { return $this->mate_id > 0; }
	public function hasStrength($trait) { 
		return $this->personality_traits['strength1'] == $trait || $this->personality_traits['strength2'] == $trait;
	}
	public function hasWeakness($trait) { 
		return $this->personality_traits['weakness1'] == $trait || $this->personality_traits['weakness2'] == $trait;
	}
	
	public function isAbleToWork($user) {
		if($this->isPregnant()) { return false; }
		if($this->isSick()) { return false; }
		if($this->isWorking()) { return false; }
		if($this->isHungry()) { return false; }
		if($this->isStudent()) { return false; }
		if($this->getOwnerID() != $user->getID() && !$this->isHireable()) { return false; }
		if($this->getOwnerID() != $user->getID() && !$user->canAfford($this->hire_price)) { return false; }
		return true;
	}
	
	public function isAbleToLearn() {
		if($this->isPregnant()) { return false; }
		if($this->isSick()) { return false; }
		if($this->isWorking()) { return false; }
		if($this->isHungry()) { return false; }
		if($this->isStudent()) { return false; }
		if(!$this->isTeenager() && !$this->isAdult()) { return false; }
	}
	
	//Public methods	
	public function breedTo($male, $userid) {
		$dateOfBirth = time() + 60 * 60 * 24 * self::DAYS_PREG;
		$query = 
			"INSERT INTO `pregnancies` (mother_id, father_id, user_id, date_birth)
			VALUES (" . $this->id . ", " . $male->getID() . ", $userid, '$dateOfBirth')";
		runDBQuery($query);
		
		$query = "UPDATE `squffies` SET `is_pregnant` = 'true' WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
	}
	
	public function setMate($mate) {		
		$query = "UPDATE `squffies` 
		SET `mate_id` = " . $mate->getID() . "
		WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
		$this->mate_id = $mate->getID();
	}
	
	public function startDegree($degree, $days) {
		$this->degree_type = 'Apprentice';
		$this->degree_id = $degree;
		
		$query = "UPDATE `squffies` 
		SET `degree_type` = 'Apprentice', `squffy_degree` = '$degree' 
		WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
		
		$date = time() + 60 * 60 * 24 * $days;
		if($this->hasStrength(Personality::SCHOOL_TRAIT)) { $date += Personality::SCHOOL_CHANGE * 60 * 60 * 24; }
		if($this->hasWeakness(Personality::SCHOOL_TRAIT)) { $date -= Personality::SCHOOL_CHANGE * 60 * 60 * 24; }
		$query = 'INSERT INTO `degree_progress` (squffy_id, date_finished) VALUES (' . $this->id . ', \'' . date("Y-m-d h:m:s",$date) . '\')';
		runDBQuery($query);
	}
	
	public function finishDegree() {
		$query = "UPDATE `squffies` 
		SET `degree_type` = 'Master'
		WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
	}
	
	public function finishJob() {
		$query = "UPDATE `squffies` 
		SET `is_working` = 'false'
		WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
	}
	
	public function feed($food) {
		$chromosome = $food->getChromosome();
		if($this->hunger < 1 && $this->$chromosome > 99) { return; }
		
		$chromosomeIncrease = $food->getChromosomeIncrease();
		if($this->hasStrength(Personality::CHROMOSOME_TRAIT)) { $chromosomeIncrease += Personality::CHROMOSOME_CHANGE; }
		if($this->hasWeakness(Personality::CHROMOSOME_TRAIT)) { $chromosomeIncrease -= Personality::CHROMOSOME_CHANGE; }
		$this->$chromosome += $chromosomeIncrease;
		if($this->$chromosome > 100) { $this->$chromosome = 100; }
		
		$hungerDecrease = $food->getHungerDecrease();
		if($this->hasStrength(Personality::HUNGER_TRAIT)) { $hungerDecrease += Personality::HUNGER_CHANGE; }
		if($this->hasWeakness(Personality::HUNGER_TRAIT)) { $hungerDecrease -= Personality::HUNGER_CHANGE; }
		$this->hunger -= $hungerDecrease;
		if($this->hunger < 0) { $this->hunger = 0; }
		
		$query = 'UPDATE `squffies` 
		SET `hunger` = ' . $this->hunger . ', `' . $chromosome . '` = ' . $this->$chromosome . ' 
		WHERE `squffy_id` = ' . $this->id;
		runDBQuery($query);
	}
	
	public function heal($doctor = NULL) {
		$this->health += mt_rand(10, 20);
		if($doctor != NULL) {
			if($doctor->hasStrength(Personality::HEAL_TRAIT)) { $this->health += Personality::HEALING_CHANGE; }
			if($doctor->hasWeakness(Personality::HEAL_TRAIT)) { $this->health -= Personality::HEALING_CHANGE; }
		}
		if($this->health > 100) { $this->health = 100; }
		$query = 'UPDATE `squffies` SET `health` = ' . $this->health . ' WHERE `squffy_id` = ' . $this->id;
		runDBQuery($query);
	}
	
	//Staff healing tree (doctor)
	//Staff nursery (nursemaid)
	//Staff farms (farmer)
	//Staff orchards (forester)
	//Staff school (teacher)
	//Staff kitchen (cook, baker)
	//Staff carpenter's shop (builder)
	
	//Private methods
	private function fetchAppearance() {
		if($this->appearance_traits != NULL) return;
		$query = 'SELECT * FROM `squffy_appearance` WHERE `squffy_id` = ' . $this->id;
		$result = runDBQuery($query);
		$this->appearance_traits = array();
		while($trait = @mysql_fetch_assoc($result)) {
			$this->appearance_traits[] = $trait;
		}		
	}
	
	private function fetchFullAppearance() {
		if($this->appearance_traits != NULL) return;
		$query = 'SELECT appearance_traits.trait_name, appearance_traits.trait_title, appearance_traits.trait_type, squffy_appearance.*
		 FROM `squffy_appearance`, appearance_traits WHERE squffy_appearance.trait_id = appearance_traits.trait_id AND `squffy_id` = ' . $this->id;
		$result = runDBQuery($query);
		$this->appearance_traits = array();
		while($trait = @mysql_fetch_assoc($result)) {
			$this->appearance_traits[] = $trait;
		}		
	}
	
	private function fetchPersonality() {
		if(array_key_exists('strength1_name', $this->personality_traits)) return;
		
		$query = 'SELECT 
		s1.trait_good_name as s1, s2.trait_good_name as s2, w1.trait_bad_name as w1, w2.trait_bad_name as w2,
		s1.trait_good_desc as s1_desc, s2.trait_good_desc as s2_desc, w1.trait_bad_desc as w1_desc, w2.trait_bad_desc as w2_desc
		FROM `personality_traits` s1, personality_traits s2, personality_traits w1, personality_traits w2
		WHERE s1.trait_id = ' . $this->personality_traits['strength1'] . ' AND ' .
		's2.trait_id = ' . $this->personality_traits['strength2'] . ' AND ' .
		'w1.trait_id = ' . $this->personality_traits['weakness1'] . ' AND ' .
		'w2.trait_id = ' . $this->personality_traits['weakness2'];
		$result = runDBQuery($query);	
		$info = @mysql_fetch_assoc($result);
		
		$this->personality_traits['strength1_name'] = $info['s1'];
		$this->personality_traits['strength2_name'] = $info['s2'];
		$this->personality_traits['weakness1_name'] = $info['w1'];
		$this->personality_traits['weakness2_name'] = $info['w2'];		
		$this->personality_traits['strength1_desc'] = $info['s1_desc'];
		$this->personality_traits['strength2_desc'] = $info['s2_desc'];
		$this->personality_traits['weakness1_desc'] = $info['w1_desc'];
		$this->personality_traits['weakness2_desc'] = $info['w2_desc'];
	}
	
	private function fetchItems() {
		if($this->items != NULL) { return; }
		
		$query = 'SELECT * FROM squffy_items WHERE squffy_id = ' . $this->id;
		$result = runDBQuery($query);		
		$this->items = array();
		
		while($info = @mysql_fetch_assoc($result)) {
			$this->items[] = $info;
		}		
	}
}
?>