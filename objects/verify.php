<?php
class Verify {
	const USERNAME_MIN_LEN = 3;
	const USERNAME_MAX_LEN = 50;
	const USERNAME_TAGS = '<b><i><u>';
	
	const LOGIN_MIN_LEN = 3;
	const LOGIN_MAX_LEN = 50;
	
	public static function VerifyUsername($username, $canExist = false) {
		if(strlen($username) < self::USERNAME_MIN_LEN) { return 'Your username must be at least ' . self::USERNAME_MIN_LEN . ' characters long.'; }
		if(strlen($username) > self::USERNAME_MAX_LEN) { return 'Your username must be less than ' . self::USERNAME_MAX_LEN . ' characters long.'; }
		if(strip_tags($username, self::USERNAME_TAGS) != $username) { return 'You may only use the &lt;b>, &lt;i> and &lt;u> tags in your username.'; }
		if(!$canExist && User::usernameTaken($username)) { return 'That username is already taken.'; }
		return false;
	}
	
	public static function VerifyLogin($login) {
		if(strlen($login) < self::LOGIN_MIN_LEN) { return 'Your login must be at least ' . self::LOGIN_MIN_LEN . ' characters long.'; }
		if(strlen($login) > self::LOGIN_MAX_LEN) { return 'Your login must be less than ' . self::LOGIN_MAX_LEN . ' characters long.'; }
		if(!ctype_alnum($login)) { return "You may only use the &lt;b>, &lt;i> and &lt;u> tags in your username."; }
		if(User::loginNameTaken($login)) { return 'That login is already taken.'; }
		return false;
	}	
}
?>