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
		$inventory;
		
	//Constructors
	public function __construct($result) {
		$info = @mysql_fetch_assoc($result);
		
		$this->id = $info['user_id'];
		$this->username = $info['username'];
		$this->level = $info['level_id'];
		$this->last_seen = $info['date_last_seen'];
		$this->layout_id = $info['default_layout_id'];
		
		$this->inventory = NULL;
	}
	
	public static function getUserByID($id) {
		$queryString = "SELECT * FROM `users` WHERE `user_id` = $id";
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
		$this->inventory = @mysql_fetch_assoc($query);
		
		$queryString = 'DELETE FROM `cache_changed` WHERE `user_id` = ' . $this->id;
        runDBQuery($queryString);
	}	
		
    public function seenNow() {
		$queryString = 'UPDATE `users` SET `date_last_seen` = now() where `user_id` = ' . $this->id;
        runDBQuery($queryString);
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

	public static function createNewUser($username, $password, $login_name){
		$salt =  randomString(self::SALT_LEN);
		$hash = self::secure($password, $salt);
		$user_id = self::InsertIntoUserTable($username);
		return self::InsertIntoUserLoginTable($user_id, $login_name, $salt, $hash);
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
	private static function InsertIntoUserTable($username){
		$queryString = "INSERT INTO users (username, level_id, default_layout_id) VALUES ('".$username."', '0', '0');";
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
}
?>