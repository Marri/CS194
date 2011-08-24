<?php
class Badge {
	const TREE_BADGE = 3;
	const GROUND_BADGE = 2;
	const BDAY_BADGE = 1;	
	const LIHTAN_BADGE = 4;	
	const FEY_BADGE = 5;	
	const SAND_BADGE = 6;	
	const CHATTER_BADGE = 7;	
	const TP_BADGE = 8;	
	const FRIEND_BADGE = 9;
	const BRAG_BADGE = 10;		
	const SHOVEL_BADGE = 11;
	
	private
		$name,
		$description;
		
	private function __construct($info) {
		$this->name = $info['badge_name'];
		$this->description = $info['badge_description'];
	}
		
	public static function GetBadgesByUserID($id) {
		$badges = array();
		
		$query = "SELECT badge_name, badge_description FROM user_badges, badges WHERE user_id = $id AND badges.badge_id = user_badges.badge_id";
		$result = runDBQuery($query);
		while($info = @mysql_fetch_assoc($result)) {
			$badges = new Badge($info);
		}
	}
	
	public static function GiveBadge($userid, $badge) {
		$query = "SELECT user_id FROM user_badges WHERE badge_id = $badge AND user_id = $userid";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) {
			$query = "INSERT INTO user_badges VALUES ($userid, $badge)";
			runDBQuery($query);
		}
	}
}
?>