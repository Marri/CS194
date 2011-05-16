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
		$base_color,
		$eye_color,
		$foot_color,		
		$c1,
		$c2,
		$c3,
		$c4,
		$c5,
		$c6,
		$c7,
		$c8,
		
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
		$this->base_color = $info['base_color'];
		$this->eye_color = $info['eye_color'];
		$this->foot_color = $info['foot_color'];
		$this->c1 = $info['c1'];
		$this->c2 = $info['c2'];
		$this->c3 = $info['c3'];
		$this->c4 = $info['c4'];
		$this->c5 = $info['c5'];
		$this->c6 = $info['c6'];
		$this->c7 = $info['c7'];
		$this->c8 = $info['c8'];
		
		//Costs
		$cost['id'] = $info['breeding_price_item_id'];
		$cost['amount'] = $info['breeding_price_item_amount'];
		$cost['sd'] = $info['breeding_price_sd'];
		$this->breed_price = new Cost($cost);
		$cost['id'] = $info['hire_price_item_id'];
		$cost['amount'] = $info['hire_price_item_amount'];
		$cost['sd'] = $info['hire_price_sd'];
		$this->hire_price = new Cost($cost);
		
		//Defaults for separately fetched information
		$this->appearance_traits = NULL;
		$this->family_tree = NULL;
		$this->items = NULL;
		$this->species = NULL;
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
		if(@mysql_num_rows($result) < 1) { return NULL; }
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
	public function getGenderDisplay() { 
		if($this->gender == 'F') { return 'Female'; }
		return 'Male'; 
	}
	public function getAge() { return $this->age; }
	public function getBirthday() { return $this->birthday; }
	public function getSpeciesID() { return $this->species_id; }
	public function getSpecies() { return $this->species; }
	public function getHunger() { return $this->hunger; }
	public function getHealth() { return $this->health; }
	public function getEnergy() { return $this->energy; }
	public function getHappiness() { return $this->happiness; }
	public function getLuck() { return $this->luck; }
	public function getC1() { return $this->c1; }
	public function getC2() { return $this->c2; }
	public function getC3() { return $this->c3; }
	public function getC4() { return $this->c4; }
	public function getC5() { return $this->c5; }
	public function getC6() { return $this->c6; }
	public function getC7() { return $this->c7; }
	public function getC8() { return $this->c8; }
	public function getDegreeName() { return $this->degree_name; }
	public function getDegreeType() { return $this->degree_type; }
	public function getOwnerID() { return $this->owner_id; }
	public function getMateID() { return $this->mate_id; }
	public function getBaseColor() { return $this->base_color; }
	public function getEyeColor() { return $this->eye_color; }
	public function getFootColor() { return $this->foot_color; }
	public function getAppearanceTraits() { return $this->appearance_traits; }
	public function getPersonalityTraits() { return $this->personality_traits; }
	public function getItems() { return $this->items; }
	public function getBreedPrice() { return $this->breed_price; }
	public function getHirePrice() { return $this->hire_price; }
	public function getFamily() { return $this->family_tree; }
	public function getMotherID() { return $this->family_tree['mother']; }
	public function getFatherID() { return $this->family_tree['father']; }
	public function getMotherMotherID() { return $this->family_tree['mother']; }
	public function getMotherFatherID() { return $this->family_tree['father']; }
	public function getFatherMotherID() { return $this->family_tree['father_mother']; }
	public function getFatherFatherID() { return $this->family_tree['father_father']; }
	
	public function getLink() { return '<a href="view_squffy.php?id=' . $this->id . '">' . $this->name . '</a>'; }
	public function getURL($make = true) { 
		$img = './images/squffies/' . floor($this->id / 1000) . '/' . $this->id . '.png'; 
		if($make && !file_exists($img)) {
			$this->fetchFullAppearance();
			$this->fetchSpecies();
			$squffy = $this;
			include('./scripts/reset_image.php');
		}
		$img = './images/squffies/' . floor($this->id / 1000) . '/' . $this->id . '.png'; 
		return $img;
	}
	public function getThumbnail($make = true) { 
		$img = './images/squffies/' . floor($this->id / 1000) . '/t' . $this->id . '.png'; 
		if($make && !file_exists($img)) {
			$this->fetchFullAppearance();
			$this->fetchSpecies();
			$squffy = $this;
			include('./scripts/reset_image.php');
		}
		$img = './images/squffies/' . floor($this->id / 1000) . '/t' . $this->id . '.png';
		return $img;
	}
	
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
	public function isTaught() { return $this->degree_name != NULL && !$this->isStudent(); }
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
		if(!$this->getGender() == 'F') { return; }
		$dateOfBirth = time() + 60 * 60 * 24 * self::DAYS_PREG;
		$date = date("Y-m-d h:m:s",$dateOfBirth);
		$query = 
			"INSERT INTO `pregnancies` (mother_id, father_id, user_id, date_birth)
			VALUES (" . $this->id . ", " . $male->getID() . ", $userid, '$date')";
		runDBQuery($query);
		
		$query = "UPDATE `squffies` SET `is_pregnant` = 'true' WHERE `squffy_id` = " . $this->id;
		runDBQuery($query);
		
		$this->is_pregnant = 'true';
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
	public function fetchSpecies() {
		if($this->species != NULL) { return; }
		$query = "SELECT species_name FROM species WHERE species.species_id = " . $this->species_id;
		$result = runDBQuery($query);
		$info = @mysql_fetch_assoc($result);
		$this->species = $info['species_name'];
	}
	
	public function fetchAppearance() {
		if($this->appearance_traits != NULL) return;
		$query = 'SELECT * FROM `squffy_appearance` WHERE `squffy_id` = ' . $this->id . '
		 ORDER BY squffy_appearance.trait_order ASC';
		$result = runDBQuery($query);
		$this->appearance_traits = array();
		while($trait = @mysql_fetch_assoc($result)) {
			$trait = new Appearance($trait);
			$this->appearance_traits[$trait->getID()] = $trait;
		}		
	}
	
	private function fetchFullAppearance() {
		if($this->appearance_traits != NULL) return;
		$query = 'SELECT appearance_traits.trait_name, appearance_traits.trait_title, appearance_traits.trait_type, squffy_appearance.*
		 FROM `squffy_appearance`, appearance_traits 
		 WHERE squffy_appearance.trait_id = appearance_traits.trait_id AND `squffy_id` = ' . $this->id . '
		 ORDER BY squffy_appearance.trait_order ASC';
		$result = runDBQuery($query);
		$this->appearance_traits = array();
		while($trait = @mysql_fetch_assoc($result)) {
			$trait = new Appearance($trait);
			$this->appearance_traits[$trait->getID()] = $trait;
		}		
	}
	
	public function refetchAppearance() { 
		$this->appearance_traits = NULL;
		$this->fetchFullAppearance();
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
	
	private function fetchFamily() {
		if($this->family_tree != NULL) { return; }
		if($this->isCustom()) { 
			$this->family_tree = array();
			$this->family_tree['mother'] = NULL;
			$this->family_tree['father'] = NULL;
			$this->family_tree['mother_mother'] = NULL;
			$this->family_tree['mother_father'] = NULL;
			$this->family_tree['father_mother'] = NULL;
			$this->family_tree['father_father'] = NULL;
			return; 
		}
		$query = 'SELECT * FROM squffy_family WHERE squffy_id = ' . $this->id;
		$result = runDBQuery($query);
		$info = @mysql_fetch_assoc($result);
		$this->family_tree = array();
		$this->family_tree['mother'] = $info['mother_id'];
		$this->family_tree['father'] = $info['father_id'];
		$this->family_tree['mother_mother'] = $info['mother_mother_id'];
		$this->family_tree['mother_father'] = $info['mother_father_id'];
		$this->family_tree['father_mother'] = $info['father_mother_id'];
		$this->family_tree['father_father'] = $info['father_father_id'];
	}
	
	//Static methods
	private static function averageChromosome($mom_c, $dad_c) {
		$min = min($mom_c, $dad_c);
		$max = max($mom_c, $dad_c);
		return mt_rand($min, $max);
	}
	
	private static function GetSpeciesFromParents($mom, $dad) {
		$species = $mom->getSpeciesID();
		if($species == $dad->getSpeciesID()) { return $species; }
		if(mt_rand(0, 500) > 250) { return $species; }
		return $dad->getSpeciesID();
	}
	
	private static function GetGenderFromParents($mom, $dad) {
		$gender = 'M';
		$max = 400 - $mom->getC8() - $dad->getC8();		
		if(mt_rand(0, $max) < 40) { $gender = 'F'; }
		return $gender;
	}
	
	public static function createChild($mother, $father, $owner) {
		$personality = Personality::GenerateTraits($mother, $father);
		$appearance = Appearance::GenerateTraits($mother, $father);
		$name = $mother->getName() . ' x ' . $father->getName();
		$gender = self::GetGenderFromParents($mother, $father);
		
		$species = self::GetSpeciesFromParents($mother, $father);
		
		$base = Appearance::GetTraitColor($mother->getBaseColor(), $father->getBaseColor());
		$eye = Appearance::GetTraitColor($mother->getEyeColor(), $father->getEyeColor());
		$foot = Appearance::GetTraitColor($mother->getFootColor(), $father->getFootColor());
		$c1 = self::averageChromosome($mother->getC1(), $father->getC1());
		$c2 = self::averageChromosome($mother->getC2(), $father->getC2());
		$c3 = self::averageChromosome($mother->getC3(), $father->getC3());
		$c4 = self::averageChromosome($mother->getC4(), $father->getC4());
		$c5 = self::averageChromosome($mother->getC5(), $father->getC5());
		$c6 = self::averageChromosome($mother->getC6(), $father->getC6());
		$c7 = self::averageChromosome($mother->getC7(), $father->getC7());
		$c8 = self::averageChromosome($mother->getC8(), $father->getC8());
		
		$query = "
		INSERT INTO `squffies` 
			(`squffy_owner`, `squffy_name`, `squffy_gender`, `squffy_birthday`, `squffy_species`, `squffy_degree`, `degree_type`, `hunger`, `health`, `energy`, `happiness`, `luck`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`, `c7`, `c8`, `base_color`, `eye_color`, `foot_color`, `is_custom`, `is_pregnant`, `is_breedable`, `is_working`, `is_hireable`, `is_in_market`, `strength1_id`, `strength2_id`, `weakness1_id`, `weakness2_id`, `mate_id`, `breeding_rights`, `rights_revert`, `num_items`) 
		VALUES
			($owner, '$name', '$gender', now(), $species, NULL, NULL, 0, 100, 100, 100, 0, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, '$base', '$eye', '$foot', 'false', 'false', 'false', 'false', 'false', 'false', " . $personality['strength1'] . ", " . $personality['strength2'] . ", " . $personality['weakness1'] . ", " . $personality['weakness2'] . ", NULL, NULL, NULL, 0);";
		runDBQuery($query);
		$id = mysql_insert_id();

		$i = 0;
		foreach($appearance as $trait) {
			$query = "
			INSERT INTO `squffy_appearance` 
				(`squffy_id`, `trait_id`, `trait_square`, `trait_color`, `trait_order`) 
			VALUES
				($id, " . $trait->getID() . ", '" . $trait->getSquare() . "', '" . $trait->getColor() . "', $i)";
			runDBQuery($query);
			$i++;
		}
		
		$mom_id = $mother->getID();
		$mom_mom_id = 'NULL';
		$mom_dad_id = 'NULL';
		if(!$mother->isCustom()) {
			$mom_mom_id = $mother->getMotherID();
			$mom_dad_id = $mother->getFatherID();
		}
		
		$dad_id = $father->getID();
		$dad_mom_id = 'NULL';
		$dad_dad_id = 'NULL';
		if(!$father->isCustom()) {
			$dad_mom_id = $father->getMotherID();
			$dad_dad_id = $father->getFatherID();
		}
		
		$query = "INSERT INTO squffy_family VALUES ($id, $mom_id, $dad_id, $mom_mom_id, $mom_dad_id, $dad_mom_id, $dad_dad_id);";
		runDBQuery($query);
	}
	
	public static function CreateCustom($name, $gender, $design, $owner) {
		$personality = Personality::RandomTraits();
		$species = $design->getSpecies();
		$base = $design->getBase();
		$eye = $design->getEye();
		$foot = $design->getFoot();
		
		$query = "
		INSERT INTO `squffies` 
			(`squffy_owner`, `squffy_name`, `squffy_gender`, `squffy_birthday`, `squffy_species`, `squffy_degree`, `degree_type`, `hunger`, `health`, `energy`, `happiness`, `luck`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`, `c7`, `c8`, `base_color`, `eye_color`, `foot_color`, `is_custom`, `is_pregnant`, `is_breedable`, `is_working`, `is_hireable`, `is_in_market`, `strength1_id`, `strength2_id`, `weakness1_id`, `weakness2_id`, `mate_id`, `breeding_rights`, `rights_revert`, `num_items`) 
		VALUES
			($owner, '$name', '$gender', now(), $species, NULL, NULL, 0, 100, 100, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, '$base', '$eye', '$foot', 'true', 'false', 'false', 'false', 'false', 'false', " . $personality['strength1'] . ", " . $personality['strength2'] . ", " . $personality['weakness1'] . ", " . $personality['weakness2'] . ", NULL, NULL, NULL, 0);";
		runDBQuery($query);
		$id = mysql_insert_id();
		
		$design->fetchTraits();
		$appearance = $design->getTraits();
		foreach($appearance as $trait) {
			$query = "
			INSERT INTO `squffy_appearance` 
				(`squffy_id`, `trait_id`, `trait_square`, `trait_color`, `trait_order`) 
			VALUES
				($id, " . $trait['id'] . ", 'S', '" . $trait['color'] . "', " . $trait['order'] . ")";
			runDBQuery($query);
		}
		
		return $id;
	}
}
?>