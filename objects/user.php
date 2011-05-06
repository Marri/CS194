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
	
	//Getters and setters
	public function getID() { return $this->id; }
	public function getHash() { return $this->hash; }
	public function getInventory() {
		if($this->inventory == NULL) { $this->fetchInventory(); }
		return $this->inventory;
	}
	public function getLayout() { return $this->layout_id; }
	public function getUsername(){ return $this->username; }
	public function getEmail(){ return $this->email_addr; }
	public function isActivated(){	return $this->activated; }
	//Predicates
	public function canAffordSD($cost) {
		if($this->inventory['squffy_dollar'] >= $cost->getSDPrice()) { return true; }
		return false;
	}
	
	public function canAffordItem($cost) {
		$itemName = $cost->getItemName();
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

	public static function createNewUser($username, $password, $login_name, $email){
		$salt =  randomString(self::SALT_LEN);
		$hash = self::secure($password, $salt);
		$user_id = self::InsertIntoUserTable($username, $email);
		self::InsertIntoUserLoginTable($user_id, $login_name, $salt, $hash);
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
	//Private methods
	private static function secure($password, $salt) {
		$hash = sha1($password . $salt);
		for($i = 0; $i < self::SALT_LEN; $i++) {
			$hash = sha1($hash);
		}
		return $hash;
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
		$queryString = "INSERT INTO inventory (user_id, cashew, squffy_dollar) VALUES ('".$user_id."', '0', '0');";
		$query = runDBQuery($queryString);
	}
}
?>