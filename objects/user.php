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
		$login,
		$level,
		$hash,
		$salt,
		$last_seen,
		$inventory;
		
	//Constructors
	public function __construct($result) {
		$info = @mysql_fetch_assoc($result);
		
		$this->id = $info['user_id'];
		$this->username = $info['username'];
		$this->login = $info['login'];
		$this->level = $info['level_id'];
		$this->hash = $info['hash'];
		$this->salt = $info['salt'];
		$this->lastSeen = $info['date_last_seen'];
		
		$this->inventory = NULL;
	}
	
	public static function getUserByID($id) {
		$query = "SELECT * FROM `users` WHERE `user_id` = $id";
		return getUser($query);
	}
	
	public static function getUserByLogin($login, $password) {
		$query = "SELECT * FROM `users` WHERE `login` = '$login'";
		$user = getUser($query);
		if($user == NULL) { return NULL; }
		$hashed = $user->secure($pass);
		if($hashed == $user->getHash()) { return $user; }
		return NULL;
	}
	
	private static function getUser($queryString) {
		$query = runDBQuery($queryString);
		if(@mysql_num_rows($query) < 1) { return NULL; }
		return (new User($info));
	}
	
	//Getters and setters
	public function getID() { return $this->id; }
	public function getHash() { return $this->hash; }
	public function getInventory() {
		if($this->inventory == NULL) { $this->fetchInventory(); }
		else { $this->checkCacheUpdate(); }
	}
	
	//Predicates
	public function canAfford($cost) {
		return true;
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
	
	//Private methods
	private function secure($password) {
		$hash = sha1($password . $this->salt);
		for($i = 0; $i < self::SALT_LEN; $i++) {
			$hash = sha1($hash);
		}
		return $hash;
	}
}
?>