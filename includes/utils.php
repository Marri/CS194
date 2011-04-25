<?php
function pluralize($string) {
	$lastChar = substr($string, -1);
	if($lastChar == 's') { return $string . "'"; }
	return $string . "'s";
}

function getID($varName, $curUser = 0, $error = "This ID does not exist") {
	$id = 0;
	if(isset($_GET[$varName])) { $id = $_GET[$varName]; }
	if($id == 0 && $curUser > 0) { $id = $curUser; }
	if(!is_numeric($id) || $id < 1) { return 0; }
	return $id;
}

function getString($varName, $default = NULL) {
	if(isset($_GET[$varName])) { return $_GET[$varName]; }
	return $default;
}

function randomString($len) {
	$newString = "";
	$for($i = 0; $i < $len; $i++) {
		$which = mt_rand(1, 3);
		if($which == 1) { $let = mt_rand(48, 57); }
		if($which == 2) { $let = mt_rand(65, 90); }
		if($which == 3) { $let = mt_rand(97, 122); }
		$newString .= chr($let);
	}
	return $newString;
}
?>