<?php
class Personality {
	const AGILITY_TRAIT = 1; //More agile
	const HEAL_TRAIT = 2; //Better doctor or midwife
	const HUNGER_TRAIT = 6; //Food is more filling
	const CHROMOSOME_TRAIT = 7; //Gains more chromosomes from food
	const SCHOOL_TRAIT = 8; //Finishes school faster
	const TEACHING_TRAIT = 3; //Better teacher or nursemaid
	const ENERGY_TRAIT = 4; //More energetic
	const HAPPY_TRAIT = 5; //Happier
	const SPEED_TRAIT = 9; //Faster
	const FARM_TRAIT = 10; //Better farmer/forester	
	const BAKE_TRAIT = 11; //Better cook/baker
	
	const CHROMOSOME_CHANGE = 1;
	const HUNGER_CHANGE = 1;
	const HEALING_CHANGE = 3;
	const SCHOOL_CHANGE = 1;
	
	const NUM_TRAITS = 11;
	
	public static function GenerateTraits($mom, $dad) {
		$options = array();	
		$strengths = array();	
		$weaknesses = array();	
		
		$mom_personality = $mom->getPersonalityTraits();
		$dad_personality = $dad->getPersonalityTraits();	
		
		array_push($strengths, $mom_personality['strength1']);
		array_push($strengths, $mom_personality['strength2']);
		array_push($strengths, $dad_personality['strength1']);
		array_push($strengths, $dad_personality['strength2']);
		array_push($weaknesses, $mom_personality['weakness1']);
		array_push($weaknesses, $mom_personality['weakness2']);
		array_push($weaknesses, $dad_personality['weakness1']);
		array_push($weaknesses, $dad_personality['weakness2']);
			
		for($i = 1; $i <= Personality::NUM_TRAITS + 4; $i++) { array_push($options, $i); }
		
		$traits = array_rand($options, 4);
		$personality = array();
		$personality['strength1'] = self::GetTrait($traits[0], $strengths);
		$personality['strength2'] = self::GetTrait($traits[1], $strengths);
		$personality['weakness1'] = self::GetTrait($traits[2], $weaknesses);
		$personality['weakness2'] = self::GetTrait($traits[3], $weaknesses);
		
		return $personality;
	}
	
	public static function RandomTraits() {
		$options = array();				
		for($i = 1; $i <= Personality::NUM_TRAITS + 4; $i++) { array_push($options, $i); }		
		$traits = array_rand($options, 4);
		$personality = array();
		$personality['strength1'] = $traits[0];
		$personality['strength2'] = $traits[1];
		$personality['weakness1'] = $traits[2];
		$personality['weakness2'] = $traits[3];
		
		return $personality;
	}
	
	private static function GetTrait($id, $replace) {
		if($id <= self::NUM_TRAITS) { return $id; }
		$id -= self::NUM_TRAITS;
		return $replace[$id - 1];
	}
}
?>