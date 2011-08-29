<?php
$hostname = 'spstrade.netfirmsmysql.com';
$database = 'squffies_v2';
$username = 'u274928';
$password = 'wearetheadmin';
/*$hostname = "127.0.0.1";
$database = "cs194";
$username = "root";
$password = 'squffies';*/

//Connect to database
$con = @mysql_connect($hostname, $username, $password) or throwMySQLError(mysql_error(), 'Could not connect to database.');
@mysql_select_db($database, $con);
@mysql_query('SET SQL_BIG_SELECTS=1;');

//Clear variables
$hostname = '';
$database = '';
$username = '';
$password = '';

//Run a database query
function runDBQuery($queryString) {
	$query = @mysql_query($queryString) or throwMySQLError(mysql_error(), $queryString);
	return $query;
}

//Handle database errors
function throwMySQLError($errorMessage, $queryString) {
	echo '<div class="errors">	'. $errorMessage . "<br>" . $queryString.'
			There was a database error! A notification has been sent to our coder, Marri, and she will look into it.
			</div>';

	
	$id = $loggedin ? $userid : NULL;
$ip = $_SERVER['REMOTE_ADDR'];
	$queryString = "INSERT INTO `log_mysql_errors` (user_id, error_time, error_text, query_string) VALUES ($id, now(), '$ip', '$errorMessage', '$queryString');";
	@mysql_query($queryString); //Don't use runDBQuery cause could cause looping
	
	//Email staff email account
	
	die();
}
?>