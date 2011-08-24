<?php
class Verify {
	const USERNAME_MIN_LEN = 3;
	const USERNAME_MAX_LEN = 50;
	const USERNAME_TAGS = '<b><i><u>';
	
	const LOGIN_MIN_LEN = 3;
	const LOGIN_MAX_LEN = 50;
	
	const PASS_MIN_LEN = 5;
	
	public static function VerifyUsername($username, $canExist = false) {
		if(strlen($username) < 1) { return 'You must pick a username.'; }
		if(strlen($username) < self::USERNAME_MIN_LEN) { return 'Your username must be at least ' . self::USERNAME_MIN_LEN . ' characters long.'; }
		if(strlen($username) > self::USERNAME_MAX_LEN) { return 'Your username must be less than ' . self::USERNAME_MAX_LEN . ' characters long.'; }
		if(strip_tags($username, self::USERNAME_TAGS) != $username) { return 'You may only use the &lt;b>, &lt;i> and &lt;u> tags in your username.'; }
		if(!$canExist && User::UsernameTaken($username)) { return 'That username is already taken.'; }
		return false;
	}
	
	public static function VerifyLogin($login, $canExist = false) {
		if(strlen($login) < 1) { return 'You must pick a login name.'; }
		if(strlen($login) < self::LOGIN_MIN_LEN) { return 'Your login must be at least ' . self::LOGIN_MIN_LEN . ' characters long.'; }
		if(strlen($login) > self::LOGIN_MAX_LEN) { return 'Your login must be less than ' . self::LOGIN_MAX_LEN . ' characters long.'; }
		if(!ctype_alnum($login)) { return "Your login must be alphanumeric."; }
		if(!$canExist && User::LoginNameTaken($login)) { return 'That login is already taken.'; }
		return false;
	}	
	
	public static function VerifyEmail($email, $canExist = false) {
		if(strlen($email) < 1) { return 'You must enter your email address.'; }
		if(strlen($email) < 6) { return 'You must enter a valid email address.'; }
		
		$pattern = '/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,4}/';
		$matched = preg_replace($pattern, '', $email);
		if(strlen($matched) > 0) { return 'You must enter a valid email address.'; }
		if(!$canExist && User::EmailTaken($email)) { return 'An account is already associated with that email address.'; }
		return false;
	}
	
	
	public static function VerifyActivation($key) {
		if(strlen($key) != 40) { return 'That is not a valid activation key.'; }
		if(!ctype_alnum($key)) { return 'That is not a valid activation key.'; }
		return false;
	}
	
	public static function VerifyPasswords($choice, $confirm = NULL) {
		$errors = array();
		
		if(strlen($choice) < 1) { $errors[] = 'You must choose a password.'; }
		elseif(strlen($choice) < self::PASS_MIN_LEN) { $errors[] = 'Your password must be at least ' . self::PASS_MIN_LEN . ' characters long.'; }
		
		if($confirm != NULL) {
			if(strlen($confirm) < 1) { $errors[] = 'You must confirm your password.'; }
			elseif(strlen($confirm) < self::PASS_MIN_LEN) { $errors[] = 'Your confirmed password must be at least ' . self::PASS_MIN_LEN . ' characters long.'; }
			
			if(sizeof($errors) < 1 && $choice != $confirm) { $errors[] = 'Your passwords must match.'; }
		}
		
		if(sizeof($errors) > 0) { return $errors; }
		return false;
	}
	
	public static function VerifyID($id) {
		if(!is_numeric($id)) { return false; }
		if($id < 1) { return false; }
		return true;
	}
}
?>