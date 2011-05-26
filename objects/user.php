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
	
	private
    	$id,
		$username,
		$level,
		$last_seen,
		$layout_id,
		$inventory,
		$email_addr,
		$activated;
		
	//Constructors
	public function __construct($result) {
		$info = @mysql_fetch_assoc($result);
		
		$this->id = $info['user_id'];
		$this->username = $info['username'];
		$this->level = $info['level_id'];
		$this->last_seen = $info['date_last_seen'];
		$this->layout_id = $info['default_layout_id'];
		$this->email_addr = $info['email_address'];
		$this->activated = $info['activated'];
		
		$this->inventory = NULL;
	}
	
	
	
	//Getters and setters
	public function getID() { return $this->id; }
	public function getHash() { return $this->hash; }
	public function getLayout() { return $this->layout_id; }
	public function getUsername(){ return $this->username; }
	public function getEmail(){ return $this->email_addr; }
	public function isActivated(){	return $this->activated; }
	public function isAdmin() { return $this->level == self::ADMIN_USER; }
	public function getInventory() {
		if($this->inventory == NULL) { $this->fetchInventory(); }
		return $this->inventory;
	}
	
	//Predicates
	public function canAffordSD($cost) {
		if($this->inventory['squffy_dollar'] >= $cost->getSDPrice()) { return true; }
		return false;
	}
	
	public function canAffordItem($cost) {
		$itemName = $cost->getColumnName();
		if($this->inventory["$itemName"] >= $cost->getItemPrice()) { return true; }
		return false;
	}
	
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
        if(@mysql_num_rows($query) <= 0){
        	self::createEmptyInventory($this->id);
        	self::fetchInventory();
        }else{
			$this->inventory = @mysql_fetch_assoc($query);
			$queryString = 'DELETE FROM `cache_changed` WHERE `user_id` = ' . $this->id;
        	runDBQuery($queryString);
		}
	}
	
	public function updateInventory($col, $change, $changeDB = false) {
		if($this->inventory != NULL) {
			$this->inventory[$col] = $this->inventory[$col] + $change;
		}
		
		if($changeDB == true) {
			$query = "UPDATE inventory SET $col = $col + $change WHERE user_id = " . $this->id;
			runDBQuery($query);
		}
	}	
		
    public function seenNow() {
		$queryString = 'UPDATE `users` SET `date_last_seen` = now() where `user_id` = ' . $this->id;
        runDBQuery($queryString);
    }	
	
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
	
	private function getNoobPack(){
		$queryString = "SELECT squffy_made FROM newbie_packs WHERE user_id='".$this->id."'";
		$results = runDBQuery($queryString);
		return (@mysql_fetch_assoc($results));
	}
	
	public function canMakeFreeGroundSquffy(){
		$squffy_made = $this->getNoobPack();
		if(($squffy_made['squffy_made'] == 'none') || ($squffy_made['squffy_made'] == 'tree')){ return true; }
		return false;
	}
	
	public function canMakeFreeTreeSquffy(){
		$squffy_made = $this->getNoobPack();
		if(($squffy_made['squffy_made'] == 'none') || ($squffy_made['squffy_made'] == 'ground')){ return true; }
		return false;
	}
	
	private function updateNoobPack($squffy_type){
		$queryString = "UPDATE newbie_packs SET squffy_made='".$squffy_type."' WHERE user_id = '".$this->id."';";
		runDBQuery($queryString);
	}
	
	/* returns true if free squffy is used */
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
	public function canSellItem($item_id, $item_name, $item_amount){
		return $this->haveItemAmount($item_id, $item_name, $item_amount);
	}
	public function canSellSquffy($squffy_id){
		if($this->ownsSquffy($squffy_id) && Lot::SquffyNotOnSale($squffy_id)){
			return true;
		}
		return false;
	}
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
			self::cacheChanged($this->id);
			self::cacheChanged($seller_id);
			// return empty string for error
			return "";
		}
		//otherwise return that the user can't buy the item
		return "Insufficient funds to buy";
	}
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
			self::cacheChanged($this->id);
			self::cacheChanged($seller_id);
			// return empty string for error
			return "";
		}
		//otherwise return that the user can't buy the item
		return "Insufficient funds to buy";
	}
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
	private function migrateOldSquffies($old_user_id){
		$queryString = "SELECT * FROM old_squffies WHERE ownerid = '".$old_user_id."'";
		$result = runDBQuery($queryString);
		while($old_squffies = @mysql_fetch_assoc($result)){
			$squffy = array();
			foreach($old_squffies as $col=>$val){
				switch(col)
				{
					case "age":
						break;
					case "generation":
						break;
					case "momid":
						break;
					case "dadid":
						break;
					case "mateid":
						break;
					case "strength":
						break;
					case "speed":
						break;
					case "agility":
						break;
					case "endurance":
						break;
					case "fertile":
						break;
					case "traitd":
						break;
					case "geneticd":
						break;
					case "xx":
						break;
					case "breedprice":
						break;
					case "breeditemname":
						break;
					case "breeditemnum":
						break;
					case "basec":
						break;
					case "eyec":
						break;
					case "feetearc":
						break;
					case "bellyc":
						break;
					case "bellys":
						break;
					case "cheetahc":
						break;
					case "cheetahs":
						break;
					case "maskc":
						break;
					case "masks":
						break;
					case "socksc":
						break;
					case "sockss":
						break;
					case "hennac":
						break;
					case "hennas":
						break;
					case "leopardc":
						break;
					case "leopards":
						break;
					case "stripesc":
						break;
					case "stripess":
						break;
					case "rainc":
						break;
					case "rains":
						break;
					case "skunkc":
						break;
					case "skunks":
						break;
					case "hoodc":
						break;
					case "hoods":
						break;
					case "paints":
						break;
					case "paintc":
						break;
					case "lemurc":
						break;
					case "lemurs":
						break;
					case "giraffec":
						break;
					case "giraffes":
						break;
					case "vines":
						break;
					case "vinec":
						break;
					case "patchesc":
						break;
					case "patchess":
						break;
					case "siamesec":
						break;
					case "siameses":
						break;
					case "wolfc":
						break;
					case "wolfs":
						break;
					case "eartipsc":
						break;
					case "eartipss":
						break;
					case "frecklesc":
						break;
					case "freckless":
						break;
					case "linec":
						break;
					case "lines":
						break;
					case "weavec":
						break;
					case "weaveo":
						break;
					case "sunc":
						break;
					case "suns":
						break;
					case "tattoos":
						break;
					case "tattooc":
						break;
					case "rootsc":
						break;
					case "rootss":
						break;
					case "harlequinc":
						break;
					case "harlequins":
						break;
					case "swirlc":
						break;
					case "swirls":
						break;
					case "marblec":
						break;
					case "marbles":
						break;
					case "burnc":
						break;
					case "burns":
						break;
					case "clawc":
						break;
					case "claws":
						break;
					case "birdwings":
						break;
					case "birdwingc":
						break;
					case "hornss":
						break;
					case "hornsc":
						break;
					case "pixiec":
						break;
					case "pixies":
						break;
					case "manec":
						break;
					case "manes":
						break;
					case "antennac":
						break;
					case "antennas":
						break;
					case "beardc":
						break;
					case "beards":
						break;
					case "whiskerc":
						break;
					case "whiskers":
						break;
					case "kirinc":
						break;
					case "kirins":
						break;
					case "antlerc":
						break;
					case "antlers":
						break;
					default:
						break; //do nothing in the default case		
			}
		}
	}
	public function migrateAccount($old_loginname, $old_password){
		$old_user_id = self::getOldUserID($old_loginname, $old_password);
		$this->migrateOldSquffies($old_user_id);//migrate squffies
		//migrate items
		//mark user account as migrated
	}
	public static function loginNameTaken($login_name){
		if($login_name == "") return true;
		$queryString = "SELECT login_name FROM user_login WHERE login_name='".$login_name."';";
		$result = runDBQuery($queryString);
		if(@mysql_num_rows($result) > 0) {
			return true;
		}
		return false;
	}
	/*
	* returns an error message depending on what's wrong with the password.
	*/
	public static function passwordValid($password){
		if($password == "") return "password field empty";
		return "";
	}
	public static function usernameTaken($username){
		if($username == "") return true;
		$queryString = "SELECT username FROM users WHERE username='".$username."';";
		$result = runDBQuery($queryString);
		if(@mysql_num_rows($result) > 0) {
			return true;
		}
		return false;
	}
	private static function AddNewbieItems($user_id){
		$user = self::getUserByID($user_id);
		$user->getInventory(); //this will create inventory if not already created elsewhere
		self::updateInventoryTable($user->getID(), "cashew", 100, "walnuts", 100);
	}
	public static function createNewUser($username, $password, $login_name, $email){
		$salt =  randomString(self::SALT_LEN);
		$hash = self::secure($password, $salt);
		$user_id = self::InsertIntoUserTable($username, $email);
		self::InsertIntoUserLoginTable($user_id, $login_name, $salt, $hash);
		self::AddNewbieItems($user_id);
		self::InsertIntoNewbieTable($user_id);
		return $user_id;
	}
	/*
	* code copy/paste from http://www.totallyphp.co.uk/code/validate_an_email_address_using_regular_expressions.htmhttp://www.totallyphp.co.uk/code/validate_an_email_address_using_regular_expressions.htm"
	*/
	public static function emailAddressInvalid($email){
		return !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email); //needs to be upgrade to preg_match(), but I don't get regexes
	}
	public static function sendActivationKey($user_id, $email){
		$act_key = self::generateActivationKey($user_id);
		$to = $email;
		$subject = "Squffy Activation Key";
		$message = "Hi Welcome to Squffies! Here's your activation link: http://squffies.com/activate?act_key=".$act_key;
		$from = "Marri@squffy.com";
		$headers = "From:" . $from;
		mail($to,$subject,$message,$headers);
	}
	public static function activateUser($given_key){
		$curr_id = self::getUserIDFromActivationKey($given_key);
		if($curr_id != NULL){
			$queryString = "UPDATE users SET activated='true' WHERE user_id = '".$curr_id."';";
			runDBQuery($queryString);
			return "";
		}else{
			return "Wrong activation key";
		}
	}
	public static function cacheChanged($user_id){
		$queryString = "INSERT INTO cache_changed VALUES('".$user_id."');";
		runDBQuery($queryString);
	}
	public static function getUserByID($id) {
		$queryString = "SELECT * FROM `users` WHERE `user_id` = '".$id."';";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		return (new User($query));
	}
	
	public static function getUserByLogin($login_name, $password) {
		$query = "SELECT * FROM `user_login` WHERE `login_name` = '$login_name'";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) < 1) { return NULL; }
		$info = @mysql_fetch_assoc($result);
		$hashed = self::secure($password, $info['salt']);
		if($info['hash'] != $hashed) { return NULL; }
		$id = $info['user_id'];
		return self::getUserByID($id);
	}
	
	//Private methods
	private static function secure($password, $salt) {
		$hash = sha1($password . $salt);
		for($i = 0; $i < self::SALT_LEN; $i++) {
			$hash = sha1($hash);
		}
		return $hash;
	}
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
	private static function generateActivationKey($user_id){
		$rand_num = rand(1000000,999999);
		$act_key =  sha1($rand_num);
		
		$queryString = "INSERT INTO user_activation (user_id, activate) VALUES ('".$user_id."', '".$act_key."')";
		runDBQuery($queryString);
		
		return $act_key;
	}
	private static function getUserIDFromActivationKey($given_key){
		$queryString = "SELECT user_id FROM user_activation WHERE activate = '".$given_key."';";
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) == 1){
			$info = @mysql_fetch_assoc($query);
			return $info['user_id'];
		}else{
			return NULL;
		}
	}
	private static function createEmptyInventory($user_id){
		$queryString = "INSERT INTO inventory (user_id) VALUES ('".$user_id."');";
		$query = runDBQuery($queryString);
	}
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