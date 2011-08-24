<?php
$selected = "account";
$forLoggedIn = true;
include("./includes/header.php");

//Auth tokens for Paypal
$valid = true;
$req = 'cmd=_notify-synch';
$tx_token = "";
if(isset($_GET['tx'])) { $tx_token = $_GET['tx']; }
$auth_token = "hrMakdCTuR79abeoGhDtcHE5T3y7hWMkf0MrxaoNk7myZG8BkP3LHyYtqu0";
$req .= "&tx=$tx_token&at=$auth_token";

//Validate with Paypal
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, '', '', 30);

if (!$fp) {
	// HTTP ERROR
	$valid = false;
} else {
	fputs ($fp, $header . $req);
	$res = '';
	$headerdone = false;
	while (!feof($fp)) {
		$line = fgets ($fp, 1024);
		if (strcmp($line, "\r\n") == 0) {
			$headerdone = true;
		} else if ($headerdone)	{
			$res .= $line;
		}
	}
	
	// parse the data
	$lines = explode("\n", $res);
	$keyarray = array();
	if (strcmp ($lines[0], "SUCCESS") == 0) {
		for ($i=1; $i<count($lines);$i++) {
			list($key,$val) = explode("=", $lines[$i]);
			$keyarray[urldecode($key)] = urldecode($val);
		}
	
		$firstname = $keyarray['first_name'];
		$lastname = $keyarray['last_name'];
		$itemname = $keyarray['item_name'];
		$amount = $keyarray['payment_gross'];
	} else if (strcmp ($lines[0], "FAIL") == 0) {
		$valid = false;
		// log for investigation
	}

	fclose ($fp);
}

if(!$keyarray) {
	$errors[] = "You must donate to receive Squffy dollars!";
	$valid = false;
}

//Check txtoken log
$query = "SELECT * FROM log_payment WHERE tx_token = '$tx_token'";
$result = runDBQuery($query);
if(@mysql_num_rows($result) > 0) {
	$errors[] = 'That payment has been credited to you already.';
	$valid = false;
}

if($valid) {
	$user->updateInventory('squffy_dollar', $amount * 10, true);
	$ip = $_SERVER['REMOTE_ADDR'];
	$query = "INSERT INTO log_payment VALUES (NULL, $userid, $amount, '$tx_token', '$ip', now())";
	runDBQuery($query);
	$notices[] = 'Success! You have purchased ' . ($amount * 10) . ' squffy dollars. Thanks!';
}

displayErrors($errors);
displayNotices($notices);

include('./includes/footer.php');
?>