<?php
/*$hostname = 'MYSQLHOST';
$database = 'd60766977';
$username = 'u70837264';
$password = '853825';*/
$hostname = "127.0.0.1";
$database = "cs194";
$username = "root";
$password = "squffy";

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
	include_once('./includes/header.php');
	echo '<div class="errors">
			There was a database error! A notification has been sent to our coder, Marri, and she will look into it.
			</div>';
	include('./includes/footer.php');
	echo $errorMessage . "<br>" . $queryString;
	
//	$id = $loggedin ? $userid : NULL;
//	$queryString = "INSERT INTO `log_mysql_errors` (user_id, error_time, error_text, query_string) VALUES ($id, now(), '$errorMessage', '$queryString');";
//	@mysql_query($queryString); //Don't use runDBQuery cause could cause looping
	
	//Email staff email account
	
	die();
}
?>