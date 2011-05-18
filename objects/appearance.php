<?php
class Appearance {	
	private
		$id,
		$name,
		$title,
		$type,
		$square,
		$order,
		$color;
		
	const BASE_DEFAULT = 'F0DDA3';
	const EYE_DEFAULT = '009900';
	const FOOT_DEFAULT = '886633';
		
	public function __construct($info) {
		$this->id = $info['trait_id'];
		$this->square = $info['trait_square'];
		$this->color = $info['trait_color'];
		$this->order = $info['trait_order'];
		
		if(isset($info['trait_name'])) {
			$this->name = $info['trait_name'];
			$this->title = $info['trait_title'];
			$this->type = $info['trait_type'];
		}
	}
	
	public function getID() { return $this->id; }
	public function getName() { return $this->name; }
	public function getTitle() { return $this->title; }
	public function getType() { return $this->type; }
	public function getSquare() { return $this->square; }
	public function getColor() { return $this->color; }
	public function getMid() { return 'C'; }
	public function getOrder() { return $this->order; }
	
	public static function GenerateTraits($mom, $dad) {
		$dad->fetchAppearance();
		$mom->fetchAppearance();
		
		$dad_traits = $dad->getAppearanceTraits();
		$mom_traits = $mom->getAppearanceTraits();
		
		$kid_traits = array();
		$i = 0;
		foreach ($dad_traits as $trait) {
			$id = $trait->getID();
			$mom_square = 'N';
			$mom_color = 'FFFFFF';
			if( isset($mom_traits[$id]) ) { 
				$mom_square = $mom_traits[$id]->getSquare();
				$mom_color = $mom_traits[$id]->getColor();  
			}
			
			$kid_info['trait_square'] = self::GetTrait($mom_square, $trait->getSquare(), $trait->getMid(), $mom->getC6(), $dad->getC6());			
			if($kid_info['trait_square'] != 'N') {
				$kid_info['trait_color'] = self::GetTraitColor($mom_color, $trait->getColor());
				$kid_info['trait_id'] = $id;
				$kid_info['trait_order'] = $i;
				$i++;
				$kid_trait = new Appearance($kid_info);
				$kid_traits[$id] = $kid_trait;
			}
		}
		
		foreach($mom_traits as $trait) {
			$id = $trait->getID();
			if(isset($kid_traits[$id])) { continue; }
			
			$kid_info['trait_square'] = self::GetTrait($trait->getSquare(), 'N', $trait->getMid(), $mom->getC6(), $dad->getC6());			
			if($kid_info['trait_square'] != 'N') {
				$kid_info['trait_color'] = self::GetTraitColor($trait->getColor(), 'FFFFFF');
				$kid_info['trait_id'] = $id;
				$kid_info['trait_order'] = $i;
				$i++;
				$kid_trait = new Appearance($kid_info);
				$kid_traits[$id] = $kid_trait;
			}
		}
		
		return $kid_traits;
	}
	
	public static function GetTraitColor($mom_color, $dad_color) {
		$mom_rgb = self::html2rgb($mom_color);
		$dad_rgb = self::html2rgb($dad_color);
		
		$kid_r = $mom_rgb[0] + mt_rand(0, $dad_rgb[0] - $mom_rgb[0]);
		$kid_g = $mom_rgb[1] + mt_rand(0, $dad_rgb[1] - $mom_rgb[1]);
		$kid_b = $mom_rgb[2] + mt_rand(0, $dad_rgb[2] - $mom_rgb[2]);
		
		return strtoupper(self::rgb2html($kid_r, $kid_g, $kid_b));
	}
	
	private static function GetTrait($mom_square, $dad_square, $mid, $mom_c, $dad_c) {
		if($dad_square == 'S') {
			if($mom_square == $dad_square) { return 'S'; }
			elseif($mom_square == 'N') {			
				if($dad_c == 0) { return $mid; }
				$rand = mt_rand(0, 200 - $dad_c);
				if($rand < 10) { return 'S'; }
				return $mid;
			} else {
				$rand = mt_rand(0, 700 - $mom_c - $dad_c);
				if($rand < 350) { return 'S'; }
				return $mid;
			}
		} elseif ($dad_square == $mid) {		
			if($mom_square == 'S') {			
				$rand = mt_rand(0, 700 - $mom_c - $dad_c);
				if($rand < 350) { return 'S'; }
				return $mid;
			} elseif ($mom_square == $mid) {
				$rand = mt_rand(0, 1200 - $mom_c - $dad_c);
				if($rand < 300) { return 'S'; }
				if($rand < 900) { return $mid; }
				return 'N';				
			} else {				
				$rand = mt_rand(0, 350 - $dad_c);
				if($rand < 175) { return $mid; }
				return 'N';
			}
		} else {
			if($mom_square == 'S') { 			
				if($mom_c == 0) { return $mid; }
				$rand = mt_rand(0, 200 - $mom_c);
				if($rand < 10) { return 'S'; }
				return $mid;
			}
				
			$rand = mt_rand(0, 350 - $mom_c);
			if($rand < 175) { return $mid; }
			return 'N';
		}
	}
	
	public static function html2rgb($color) {
	  list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
	  $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	  return array($r, $g, $b);
	}
	
	public static function rgb2html($r, $g, $b) {
		if (is_array($r) && sizeof($r) == 3)
			list($r, $g, $b) = $r;
	
		$r = intval($r); 
		$g = intval($g);
		$b = intval($b);
	
		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));
	
		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return $color;
	}
	
	public static function Colors() {
		return array(
			"pink" => "FFBBBB",
			"red" => "BB0000", 
			"orange" => "FF8800",
			"yellow" => "FFFF00", 
			"green" => "009900", 
			"teal" => "33BBBB",
			"darkblue" => "000080",
			"blue" => "0000BB", 
			"lightblue" => "8888ff",
			"purple" => "DD99FF",
			"gray" => "999999", 
			"grey" => "999999", 
			"brown" => "886633",
			"black" => "000000",
			"white" => "FFFFFF"
		);
	}
}
?>