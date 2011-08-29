<?php
class User {
	const ADMIN_USER = 1;
	const MOD_USER = 2;
	const PERM_UPGRADE_USER = 3;
	const UPGRADE_USER = 4;
	const NORMAL_USER = 5;
	const PRE_ACTIVATED_USER = 6;
	const RESET_PASSWORD_USER = 7;
	const FROZEN_USER = 8;
	const VACATION_USER = 9;
	
	const SALT_LEN = 15;
	const RESET_LEN = 8;
	
	private
    	$id,
		$username,
		$level,
		$last_seen,
		$layout_id,
		$inventory,
		$email_addr,
		$date_deupgrade;
		
	//Constructors
	public function __construct($result) {
		$info = @mysql_fetch_assoc($result);
		
		$this->id = $info['user_id'];
		$this->username = $info['username'];
		$this->level = $info['level_id'];
		$this->last_seen = $info['date_last_seen'];
		$this->layout_id = $info['default_layout_id'];
		$this->email_addr = $info['email_address'];
		$this->date_deupgrade = $info['date_upgrade_ends'];
		
		$this->inventory = NULL;
	}	
	
	//Getters and setters
	public function getID() { return $this->id; }
	public function getLayout() { return $this->layout_id; }
	public function getUsername(){ return $this->username; }
	public function getEmail(){ return $this->email_addr; }
	public function getLevel() { return $this->level; }
	public function getLastSeen() { return $this->last_seen; }
	public function getDateUpgradeEnds() { return $this->date_deupgrade; }
	public function getInventory() {
		if($this->inventory == NULL) { $this->fetchInventory(); }
		return $this->inventory;
	}
	public function getAmount($col) {
		$inventory = $this->getInventory();
		if(!isset($inventory[$col])) { return 0; }
		return $inventory[$col];
	}
	public function getLink() { return '<a href="profile.php?id=' . $this->id . '">' . $this->username . '</a>'; }
	public function getLevelName() {
		switch($this->level) {
			case self::ADMIN_USER: return 'Administrator';
			case self::MOD_USER: return 'Moderator';
			case self::PERM_UPGRADE_USER: case self::UPGRADE_USER: return 'Upgraded';
			case self::NORMAL_USER: case self::PRE_ACTIVATED_USER: return 'Standard';
			case self::FROZEN_USER: return 'Frozen';
			case self::RESET_PASSWORD_USER:
				$query = "SELECT * FROM log_password_reset WHERE user_id = " . $this->id . " AND old_level != " . User::RESET_PASSWORD_USER . " ORDER BY date_reset DESC LIMIT 1";
				$result = runDBQuery($query);
				$info = @mysql_fetch_assoc($result);
				$this->level = $info['old_level'];
				$name = $this->getLevelName();
				$this->level = self::RESET_PASSWORD_USER;
				return $name;				
			default: return 'On vacation';
		}
	}
	
	public function getHash() {
		$query = "SELECT hash FROM user_login WHERE user_id = " . $this->id;
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		return $info['hash'];
	}
	
	public function setLevel($level) {
		$query = 'UPDATE `users` SET `level_id` = ' . $level . ' WHERE `user_id` = ' . $this->id;
		runDBQuery($query);
		$this->level = $level;
	}
	
	public function setPassword($newPass) {
		$newSalt = randomString(self::SALT_LEN);
		$newHash = self::Secure($newPass, $newSalt);
		
		$query = "UPDATE `user_login` SET `hash` = '$newHash', `salt` = '$newSalt' WHERE `user_id` = " . $this->id;
		runDBQuery($query);
	}
	
	//Predicates
	public function isActivated(){	return $this->activated; }
	public function isAdmin() { return $this->level == self::ADMIN_USER; }
	public function isMod() { return $this->level == self::MOD_USER; }
	public function isPermUpgraded() { return $this->level == self::PERM_UPGRADE_USER; }
	public function isUpgraded() { return $this->level == self::UPGRADE_USER; }
	public function hasUpgrade() { return $this->isAdmin() || $this->isMod() || $this->isPermUpgraded() || $this->isUpgraded(); }
		
	//Public methods	
	public function checkCacheUpdate() {
		$queryString = 'SELECT * FROM `cache_changed` WHERE `user_id` = ' . $this->id;
        $query = runDBQuery($queryString);
		if(@mysql_num_rows($query) > 0) {
			$this->fetchInventory();
		}
	}	
	
	public function fetchInventory() {
		$queryString = 'SELECT * FROM `inventory` WHERE `user_id` = ' . $this->id;
        $query = runDBQuery($queryString);
		$this->inventory = @mysql_fetch_assoc($query);
		$queryString = 'DELETE FROM `cache_changed` WHERE `user_id` = ' . $this->id;
		runDBQuery($queryString);
	}
	
	public function updateInventory($col, $change, $changeDB = false) {
		if($this->inventory != NULL) {
			$this->inventory[$col] = $this->inventory[$col] + $change;
		}
		
		if($changeDB) {
			$query = "UPDATE inventory SET $col = $col + $change WHERE user_id = " . $this->id;
			runDBQuery($query);
		}
	}	
	
    public function seenNow() {
		$queryString = 'UPDATE `users` SET `date_last_seen` = now() where `user_id` = ' . $this->id;
        runDBQuery($queryString);
    }
	
	public function resetPassword() {
		$newPass = randomString(self::RESET_LEN);
		$newSalt = randomString(self::SALT_LEN);
		$newHash = self::Secure($newPass, $newSalt);
		
		$query = "UPDATE `user_login` SET `hash` = '$newHash', `salt` = '$newSalt' WHERE `user_id` = " . $this->id;
		runDBQuery($query);
		
		$this->setLevel(User::RESET_PASSWORD_USER);
		
		$subj="Your Squffies.com password has been reset.";
		$message="Dear ". stripslashes($this->username) . ",\n\nYour Squffies password has been temporarily changed to $newPass. Please log in and pick a new password as soon as you can.\n\nThanks,\n-The Squffies team";
		$headers="From:support@squffies.com";
		//mail($email,$subj,$message,$headers);
		echo $message;
	}
	
	public function addSixMonths() {
		if($this->getLevel() != self::UPGRADE_USER) { return; }
		
		$date = strtotime($this->date_deupgrade);
		if($this->date_deupgrade == NULL) { 
			$date = time();
		}
		
		$year = (int) date("Y", $date);
		$month = (int) date("m", $date);
		$month += 6;
		if($month > 12) { $month -= 12; $year++; }
		$six = $year . '-';
		if($month < 10) { $six .= '0'; }
		$six .= $month . '-' . date("d H:i:s", $date);
		
		$query = "UPDATE users SET date_upgrade_ends = '$six' WHERE user_id = " . $this->id;
		runDBQuery($query);
		$this->date_deupgrade = $six;
	}
	
	public function addDaysToUpgrade($days) {
		if($this->getLevel() != self::UPGRADE_USER) { return; }
		$date = date("Y-m-d H:i:s", strtotime($this->date_deupgrade));
		$date = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +" . $days . " days");
		$date = date("Y-m-d H:i:s", $date);
		
		$query = "UPDATE users SET date_upgrade_ends = '$date' WHERE user_id = " . $this->id;
		runDBQuery($query);
		$this->date_deupgrade = $date;
	}
	
	public function securePassword($password) {
		$query = "SELECT salt FROM user_login WHERE user_id = " . $this->id;
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		$salt = $info['salt'];
		return self::Secure($password, $salt);
	}
	
	//Private functions
	private static function Secure($password, $salt) {
		$hash = sha1($password . $salt);
		for($i = 0; $i < self::SALT_LEN; $i++) {
			$hash = sha1($hash);
		}
		return $hash;
	}
	
	private static function InsertUser($username, $email) {
		$query = "INSERT INTO users (username, level_id, default_layout_id, email_address) 
						VALUES ('" . $username . "', '" . self::PRE_ACTIVATED_USER . "', NULL, '".$email."');";
		runDBQuery($query);
		return @mysql_insert_id();
	}
	
	private static function InsertLogin($user_id, $login_name, $salt, $hash) {
		$query = "INSERT INTO user_login (login_name, user_id, hash, salt)
					VALUES ('$login_name', '$user_id', '$hash', '$salt')";
		runDBQuery($query);
	}
	
	private static function InsertNewbiePack($user_id) {
		$query = "INSERT INTO newbie_packs (user_id) VALUES ($user_id)";
		runDBQuery($query);
		$user = self::getUserByID($user_id);
		$user->updateInventory("cashew", 50, true);
		$user->updateInventory("pistachio", 3, true);
		$user->updateInventory("chestnut", 3, true);
		$user->updateInventory("pecan", 3, true);
		$user->updateInventory("walnut", 5, true);
		$user->updateInventory("almond", 3, true);
		$user->updateInventory("strawberry", 3, true);
		$user->updateInventory("hoe", 1, true);
		$user->updateInventory("shovel", 1, true);
		$user->updateInventory("water_pail", 3, true);
	}
	
	private static function InsertInventory($user_id) {
		$query = "INSERT INTO inventory (user_id) VALUES ($user_id)";
		runDBQuery($query);
		$query = "INSERT INTO pantry (user_id) VALUES ($user_id)";
		runDBQuery($query);
	}
	
	private static function SendActivationKey($user_id, $email, $user_name){
		$key = randomString(self::SALT_LEN);
		$key = sha1($key);
		
		$query = "INSERT INTO user_activation (user_id, activate) VALUES ('".$user_id."', '".$key."')";
		runDBQuery($query);
		self::SendActivationEmail($email, $user_name, $key);
	}
	
	//Static functions	
	public static function getUserByID($id) {
		$queryString = "SELECT * FROM `users` WHERE `user_id` = '".$id."';";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		return (new User($query));
	}
	
	public static function getUserByLogin($login_name, $password, $skip = false) {
		$query = "SELECT * FROM `user_login` WHERE `login_name` = '$login_name'";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		if($skip) { return self::getUserByID($info['user_id']); }
		$hashed = self::Secure($password, $info['salt']);
		if($info['hash'] != $hashed) { return NULL; }
		$id = $info['user_id'];
		return self::getUserByID($id);
	}
	
	public static function GetUserByUsername($username) {
		$queryString = "SELECT * FROM `users` WHERE `username` = '".$username."';";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		return (new User($query));
	}
	
	public static function GetUserByActivation($key) {
		$query = "SELECT * FROM `user_activation` WHERE `activate` = '$key'";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		$id = $info['user_id'];
		return self::getUserByID($id);
	}
	
	public static function GetUserByEmail($email) {
		$query = "SELECT * FROM `users` WHERE `email_address` = '$email'";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		return (new User($result));
	}
	
	public static function CreateUser($user_name, $password, $login_name, $email) {
		$salt = randomString(self::SALT_LEN);
		$hash = self::Secure($password, $salt);
		$user_id = self::InsertUser($user_name, $email);
		self::InsertLogin($user_id, $login_name, $salt, $hash);
		self::InsertInventory($user_id);
		self::InsertNewbiePack($user_id);
		self::SendActivationKey($user_id, $email, $user_name);
		return $user_id;
	}
	
	public static function SendActivationEmail($email, $user_name, $key) {
		$subj="Activate your Squffies.com account";
		$message="Welcome to Squffies, ". stripslashes($user_name) . "!  We're thrilled to have you.\n\n
		To activate your account and start using the site, please click the following link:\n\n";
		$message.="http://www.squffies.com/activate.php?key=$key\n\nThanks,\n-The Squffies team";
		$headers="From:support@squffies.com";
		mail($email, $subj, $message, $headers);
	}
	
	public static function EmailTaken($email) {
		$query = "SELECT 1 FROM `users` WHERE `email_address` = '$email'";
		$result = runDBQuery($query);
		return @mysql_num_rows($result) > 0;
	}
	
	public static function UsernameTaken($username){
		$queryString = "SELECT 1 FROM users WHERE username='".$username."';";
		$result = runDBQuery($queryString);
		return @mysql_num_rows($result) > 0;
	}
	
	public static function LoginNameTaken($login_name){
		$queryString = "SELECT 1 FROM user_login WHERE login_name='".$login_name."';";
		$result = runDBQuery($queryString);
		return @mysql_num_rows($result) > 0;
	}
	
	public static function CacheChanged($user_id){
		$queryString = "INSERT INTO cache_changed VALUES('".$user_id."');";
		runDBQuery($queryString);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//TODO!!!!!!
	
			
	
	//TODO
	public function getNotifications(){
		$notification_list = array();		
		$queryString = "SELECT * FROM notifications WHERE user_id = '".$this->id."';";
		$results = runDBQuery($queryString);
		while($notes = @mysql_fetch_assoc($results)){
			$note = new Notification($notes);
			array_push($notification_list, $note);
		}
		return $notification_list;
	}
	
	//TODO
	private function getNoobPack(){
		$queryString = "SELECT squffy_made FROM newbie_packs WHERE user_id='".$this->id."'";
		$results = runDBQuery($queryString);
		return (@mysql_fetch_assoc($results));
	}
	
	//TODO
	public function canMakeFreeGroundSquffy(){
		$squffy_made = $this->getNoobPack();
		if(($squffy_made['squffy_made'] == 'none') || ($squffy_made['squffy_made'] == 'tree')){ return true; }
		return false;
	}
	
	//TODO
	public function canMakeFreeTreeSquffy(){
		$squffy_made = $this->getNoobPack();
		if(($squffy_made['squffy_made'] == 'none') || ($squffy_made['squffy_made'] == 'ground')){ return true; }
		return false;
	}
	
	//TODO
	private function updateNoobPack($squffy_type){
		$queryString = "UPDATE newbie_packs SET squffy_made='".$squffy_type."' WHERE user_id = '".$this->id."';";
		runDBQuery($queryString);
	}
	
	/* returns true if free squffy is used */
	//TODO
	public function  useFreeSquffy($squffy_type){
		$squffy_made = $this->getNoobPack();
		$squffy_just_made = "";
		if(($squffy_made['squffy_made'] == 'none')){
			 $squffy_just_made = $squffy_type;
		}elseif(($squffy_made['squffy_made'] == 'ground') || ($squffy_made['squffy_made'] == 'tree')){
			$squffy_just_made = 'both';
		}else{
			return false;
		}
		$this->updateNoobPack($squffy_just_made);
		return true;
	}
	
	/*
	* function to determine if item in specified amount is owned by the user
	* and NOT on sale in a current lots
	*/
	//TODO
	private function haveItemAmount($item_id, $item_name, $item_amount){
		$inventory = $this->getInventory();
		$item_name = str_ireplace(" ", "_", strtolower($item_name));
		$available_amount = $inventory[$item_name] - Lot::AmountItemOnSale($this->id, $item_id);
		if($available_amount >= $item_amount) return true;
		return false;
	}
	
	/*
	 * takes in an item name and item amount to determine if the user
	 * can buy it
	 */
	//TODO
	public function canSellItem($item_id, $item_name, $item_amount){
		return $this->haveItemAmount($item_id, $item_name, $item_amount);
	}
	//TODO
	public function canSellSquffy($squffy_id){
		if($this->ownsSquffy($squffy_id) && Lot::SquffyNotOnSale($squffy_id)){
			return true;
		}
		return false;
	}
	//TODO
	public function ownsSquffy($squffy_id){
		$queryString = "SELECT squffy_owner FROM squffies WHERE squffy_id='".$squffy_id."'";
		$query = runDBQuery($queryString);
		while($squffies = mysql_fetch_assoc($query)) {
			if($squffies['squffy_owner'] == $this->id) return true;
		}
		return false;
	}
	/*
	* returns an error message when buying fails
	*/
	//TODO
	public function buyItem($lot_id, $sale_id, $sale_name, $sale_amount, $want_id, $want_name, $want_amount, $seller_id){
		//check if user can buy possibly check to see if lot is already finished
		$finished = Lot::LotFinished($lot_id);//keep users from buying using a finished lot
		if($finished) return "Lot already sold";		
		if($this->haveItemAmount($want_id, $want_name, $want_amount)){
			//mark lot as finished
			Lot::FinishLot($lot_id);
			// and transfer items
			self::updateInventoryTable($this->getID(), str_ireplace(" ", "_", strtolower($sale_name)), $sale_amount, str_ireplace(" ", "_", strtolower($want_name)), -$want_amount);
			self::updateInventoryTable($seller_id, str_ireplace(" ", "_", strtolower($sale_name)), -$sale_amount, str_ireplace(" ", "_", strtolower($want_name)), $want_amount);
			self::CacheChanged($this->id);
			self::CacheChanged($seller_id);
			// return empty string for error
			return "";
		}
		//otherwise return that the user can't buy the item
		return "Insufficient funds to buy";
	}
	//TODO
	public function buySquffy($lot_id, $squffy_id, $want_id, $want_name, $want_amount, $seller_id){
		//check if user can buy possibly check to see if lot is already finished
		$finished = Lot::LotFinished($lot_id);//keep users from buying using a finished lot
		if($finished == 'true') return "Lot already sold";		
		if($this->haveItemAmount($want_id, $want_name, $want_amount)){
			//mark lot as finished
			Lot::FinishLot($lot_id);
			// and transfer items
			self::updateInventoryTable($this->getID(), str_ireplace(" ", "_", strtolower($want_name)), -$want_amount, "", 0);
			self::updateInventoryTable($seller_id, str_ireplace(" ", "_", strtolower($want_name)), $want_amount, "", 0);
			Squffy::ChangeSquffyOwner($squffy_id, $this->getID());
			self::CacheChanged($this->id);
			self::CacheChanged($seller_id);
			// return empty string for error
			return "";
		}
		//otherwise return that the user can't buy the item
		return "Insufficient funds to buy";
	}
	//TODO
	public static function getOldUserID($login_name, $password){
		$hashword = sha1($password);
		$queryString = "SELECT userid WHERE loginname='".$login_name."' AND hashword='".$hashword."'";
		$result = runDBQuery($queryString);
		if(@mysql_num_rows($result) > 0) {
			$user_id = mysql_fetch_assoc($result);
			return $user_id['userid'];
		}
		return NULL;
	}
	//TODO
	private function calcBirthday($age, $today_date){
		$birthday = strtotime ( '-'.$age.' day' , strtotime ( $today_date ) ) ;
		$birthday = date ( "Y-m-d H:i:s" , $birthday );
		return $birthday;
	}
	//TODO
	private function setTraitColor(&$squffy_array, $trait, $color){
		if($color != NULL) { $squffy_array[$trait] = $color; }
	}
	//TODO
	private function setTraitStrength(&$squffy_array, $trait, $strength){
		if($strength > 0){
			$value = "";
			if($strength == 5){
				$value = "C";
			}else{
				$value= "S";
			}
			$squffy_array[$trait] = $value;
		}
	}
	//TODO
	private function insertSquffy(&$squffy, &$squffy_appearance, &$trait_ids){
		$personality = Personality::RandomTraits();
		$squffyInsert = 'INSERT INTO squffies 
					(squffy_owner, squffy_name, squffy_gender, squffy_birthday, squffy_species, c1, c2, c3, c4, c5, c6, c7, c8, base_color, eye_color, foot_color, is_custom, strength1_id, strength2_id, weakness1_id, weakness2_id, mate_id, breeding_price_sd, breeding_price_item_id, breeding_price_item_amount)
					 VALUES ("'.$this->id.'", "'.$squffy['squffy_name'].'", "'.$squffy['squffy_gender'].'", "'.$squffy['squffy_birthday'].'", "'.$squffy['squffy_species'].'", "'.$squffy['c1'].'", "'.$squffy['c2'].'", "'.$squffy['c3'].'", "'.$squffy['c4'].'", "'.$squffy['c5'].'", "'.$squffy['c6'].'", "'.$squffy['c7'].'", "'.$squffy['c8'].'",
					"'.$squffy['base_color'].'", "'.$squffy['eye_color'].'", "'.$squffy['foot_color'].'", "'.$squffy['is_custom'].'", "'.$personality['strength1'].'", "'.$personality['strength2'].'", "'.$personality['weakness1'].'", "'.$personality['weakness2'].'", "'.$squffy['mate_id'].'", "'.$squffy['breeding_price_sd'].'", "'.$squffy['breeding_price_item_id'].'"
					, "'.$squffy['breeding_price_item_amount'].'");';
		runDBQuery($squffyInsert);
		$squffy_id = @mysql_insert_id();
		$trait_order = 0;
		foreach ($squffy_appearance as $col=>$val){
			switch(substr($col,-1))
			{
				case "s":
					$trait_name = substr($col,0,strlen($col)-1);
					$id = $trait_ids[$trait_name];
					$trait_order++;
					$trait_color = 'FFFFFF';
					if(isset($squffy_appearance[$trait_name.'c'])) { $trait_color = $squffy_appearance[$trait_name.'c']; }
					$appearanceInsert = "INSERT INTO squffy_appearance VALUES ('".$squffy_id."', '".$id."', '".$val."', '".$trait_color."', ".$trait_order.");";
					runDBQuery($appearanceInsert);
					break;
				default:
					break;
			}
		}
	}
	//TODO
	public function migrateOldSquffies($old_user_id){
		$queryString = "SELECT * FROM old_squffies WHERE ownerid = '".$old_user_id."'";
		$result = runDBQuery($queryString);
		$today_date = date("Y-m-d H:i:s");
		$trait_ids = Appearance::getTraitIdNameMap();

		while($old_squffies = @mysql_fetch_assoc($result)){
			$squffy = array();
			$squffy_appearance = array();
			foreach($old_squffies as $col=>$val){
				switch($col)
				{
					case "age":
						$squffy['squffy_birthday'] = $this->calcBirthday($val, $today_date);
						break;
					case "name":
						$squffy['squffy_name'] = $val;
						break;
					case "gender":
						$squffy['squffy_gender'] = $val;
						break;
					case "species":
						if($val == 'tree') { $squffy['squffy_species'] = 1; }
						if($val == 'ground') { $squffy['squffy_species'] = 2; }
						if($val == 'sand') { $squffy['squffy_species'] = 3; }
						if($val == 'lihtan') { $squffy['squffy_species'] = 4; }
						if($val == 'fey') { $squffy['squffy_species'] = 5; }
						break;
					case "generation":
						if($val == 1) $squffy['is_custom'] = true;
						else $squffy['is_custom'] = false;
						break;
					case "momid":
						$squffy["mother_id"] = $val;
						break;
					case "dadid":
						$squffy["father_id"] = $val;
						break;
					case "mateid":
						$squffy["mate_id"] = $val;
						break;
					case "strength":
						$squffy["c1"] = $val;
						break;
					case "speed":
						$squffy["c2"] = $val;
						break;
					case "agility":
						$squffy["c3"] = $val;
						break;
					case "endurance":
						$squffy["c4"] = $val;
						break;
					case "fertile":
						$squffy["c5"] = $val;
						break;
					case "traitd":
						$squffy["c6"] = $val;
						break;
					case "geneticd":
						$squffy["c7"] = $val;
						break;
					case "xx":
						$squffy["c8"] = $val;
						break;
					case "breedprice":
						$squffy['breeding_price_sd'] = $val;
						break;
					case "breeditemname":
						$squffy['breeding_price_item_id'] = $val;
						break;
					case "breeditemnum":
						$squffy['breeding_price_item_amount'] = $val;
						break;
					case "basec":
						$squffy['base_color'] = $val;
						break;
					case "eyec":
						$squffy['eye_color'] = $val;
						break;
					case "feetearc":
						$squffy['foot_color'] = $val;
						break;
					case "bellyc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "bellys":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "cheetahc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "cheetahs":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "maskc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "masks":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "socksc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "sockss":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "hennac":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "hennas":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "leopardc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "leopards":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "stripesc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "stripess":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "rainc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "rains":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "skunkc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "skunks":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "hoodc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "hoods":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "paints":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "paintc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "lemurc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "lemurs":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "giraffec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "giraffes":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "vines":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "vinec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "patchesc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "patchess":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "siamesec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "siameses":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "wolfc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "wolfs":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "eartipsc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "eartipss":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "frecklesc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "freckless":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "linec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "lines":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "weavec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "weaveo":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "sunc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "suns":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "tattoos":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "tattooc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "rootsc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "rootss":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "harlequinc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "harlequins":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "swirlc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "swirls":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "marblec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "marbles":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "burnc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "burns":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "clawc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "claws":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "birdwings":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "birdwingc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "hornss":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "hornsc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "pixiec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "pixies":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "manec":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "manes":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "antennac":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "antennas":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "beardc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "beards":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "whiskerc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "whiskers":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "kirinc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "kirins":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;
					case "antlerc":
						$this->setTraitColor($squffy_appearance, $col, $val);
						break;
					case "antlers":
						$this->setTraitStrength($squffy_appearance, $col, $val);
						break;	
					default:
						break; //do nothing in the default case	
				}	
			}
			$this->insertSquffy($squffy, $squffy_appearance, $trait_ids);
		}
	}
	//TODO
	private function getOldItems($user_id){
		$old_items = array();
		$queryString = "SELECT itemname FROM old_items WHERE ownerid='".$user_id."'";
		$items = runDBQuery($queryString);
		while($item = @mysql_fetch_assoc($items)){
			if(!isset($old_items[$item['itemname']])){
				$old_items[$item['itemname']] = 0;
			}else{
				$old_items[$item['itemname']]++;
			}
		}
		return $old_items;
	}
	//TODO
	private function getOldNuts($user_id){
		$old_nuts = array();
		$queryString = "SELECT * FROM old_nutpile WHERE userid='".$user_id."'";
		$result = runDBQuery($queryString);
		$nuts = @mysql_fetch_assoc($result);
		foreach($nuts as $col=>$val){
			if($col != 'userid'){
				$nut = substr($col, 1,strlen($col)-2);
				if(!isset($old_nuts[$nut])){
					$old_nuts[$nut] = $val;
				}else{
					$old_nuts[$nut] = $old_nuts[$nut] + $val;
				}
			}
		}
		return $old_nuts;
	}
	//TODO
	private function migrateItemsAndNuts($user_id){
		$inventory = $this->getInventory();
		$old_items = $this->getOldItems($user_id);//get items
		$old_nuts = $this->getOldNuts($user_id);//get nuts
		//add nuts and items to inventory in massive update string
	}
	//TODO
	public function migrateAccount($old_loginname, $old_password){
		$old_user_id = self::getOldUserID($old_loginname, $old_password);
		$this->migrateOldSquffies($old_user_id);//migrate squffies
		$this->migrateItemsAndNuts($old_user_id);//migrate items
		//migrate farms
		//mark user account as migrated
	}
	//TODO
	public static function passwordValid($password){
		if($password == "") return "password field empty";
		return "";
	}
	//TODO
	private static function AddNewbieItems($user_id){
		$user = self::getUserByID($user_id);
		$user->getInventory(); //this will create inventory if not already created elsewhere
		self::updateInventoryTable($user->getID(), "cashew", 100, "walnuts", 100);
	}
	
	
	//Private methods
	/*
	* function to insert new user into newbie_pack table
	* returns pack_id.
	*/
	private static function InsertIntoNewbieTable($user_id){
		$queryString = "INSERT INTO newbie_packs (user_id) VALUES ('".$user_id."');";
		$result = runDBQuery($queryString);
		return mysql_insert_id();
	}
	/*
	* function to insert new user into users table
	*/
	private static function InsertIntoUserTable($username, $email){
		$queryString = "INSERT INTO users (username, level_id, default_layout_id, email_address) VALUES ('".$username."', '0', '0', '".$email."');";
		$result = runDBQuery($queryString);
		return mysql_insert_id();
	}
	/*
	* function to insert new user date into user_login table
	*/
	private static function InsertIntoUserLoginTable($user_id, $login_name, $salt, $hash){
		$queryString = "INSERT INTO user_login (user_id, login_name, salt, hash) VALUES ('".$user_id."', '".$login_name."', '".$salt."','".$hash."');";
		$result = runDBQuery($queryString);
		return $result;
	}
	//TODO
	public static function updateInventoryTable($user_id, $item1_col, $item1_change, $item2_col, $item2_change){
		$updateString = "";
		if($item2_change != 0){
			$selectString = 'SELECT '.$item1_col.','.$item2_col.' FROM `inventory` WHERE `user_id` = ' . $user_id;
	        $select = runDBQuery($selectString);
	        $item_amounts = @mysql_fetch_assoc($select);
	        $item1_amount = $item_amounts[$item1_col] + $item1_change;
	        $item2_amount = $item_amounts[$item2_col] + $item2_change;
	        
	        $updateString = "UPDATE inventory SET ".$item1_col."='".$item1_amount."', ".$item2_col."='".$item2_amount."' WHERE user_id='".$user_id."';";
	    }else{
			$selectString = 'SELECT '.$item1_col.' FROM `inventory` WHERE `user_id` = ' . $user_id;
	        $select = runDBQuery($selectString);
	        $item_amounts = @mysql_fetch_assoc($select);
	        $item1_amount = $item_amounts[$item1_col] + $item1_change;
	        	        
	        $updateString = "UPDATE inventory SET ".$item1_col."='".$item1_amount."' WHERE user_id='".$user_id."';";	    
		}
		runDBQuery($updateString);
	}
}
?>