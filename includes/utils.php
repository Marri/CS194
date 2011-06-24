<?php
function pluralize($string) {
	$lastChar = substr($string, -1);
	if($lastChar == 'y') { return substr($string, 0, strlen($string) - 1) . 'ies'; }
	if($lastChar == 's') { return $string; }
	return $string . 's';
}

function possessive($string) {
	$lastChar = substr($string, -1);
	if($lastChar == 's') { return $string . "'"; }
	return $string . "'s";
}

function inventoryItem($item) {
	$name = ucfirst(str_replace("_", " ", $item));
	return $name;
}

function checked($bool) {
	if($bool) echo ' checked';
}

function selected($bool) {
	if($bool) echo ' selected';
}

function convertBoolean($bool) {
	if($bool) return 'true';
	return 'false';
}

function row($cur) {
	echo ' class="' . $cur . '"';
	return $cur == "odd" ? "even" : "odd";
}

function classRow($cur) {
	echo $cur;
	return $cur == "odd" ? "even" : "odd";
}

function lastRow($cur) {
 	echo ' class="' . ($cur == "odd" ? "even" : "odd") . '"';
}

function getID($varName, $defaultID = 0) {
	$id = 0;
	if(isset($_GET[$varName])) { $id = $_GET[$varName]; }
	elseif(isset($_POST[$varName])) { $id = $_POST[$varName]; }
	if($id == 0 && $defaultID > 0) { $id = $defaultID; }
	if(!is_numeric($id) || $id < 1) { return 0; }
	return $id;
}

function getString($varName, $default = NULL) {
	if(isset($_GET[$varName])) { return $_GET[$varName]; }
	return $default;
}

function randomString($len) {
	$newString = "";
	for($i = 0; $i < $len; $i++) {
		$which = mt_rand(1, 3);
		if($which == 1) { $let = mt_rand(48, 57); }
		if($which == 2) { $let = mt_rand(65, 90); }
		if($which == 3) { $let = mt_rand(97, 122); }
		$newString .= chr($let);
	}
	return $newString;
}

function displayErrors($errors) {
	if(sizeof($errors) < 1) { return; }
	echo '<div class="errors">';
	foreach($errors as $error) { echo $error . '<br />'; }
	echo '</div>';
}

function displayNotices($notices) {
	if(sizeof($notices) < 1) { return; }
	echo '<div class="success">';
	foreach($notices as $notice) { echo $notice . '<br />'; }
	echo '</div>';
}

function drawMenuTop($title, $links) {
	echo '<table class="width100p" cellspacing="0">
	<tr>
	<th class="content-header width200">Menu</th>
	<th class="content-header">' . $title . '</th>
	</tr>
	<tr>
	<td class="vertical-top" style="background-color: #fff3ba; height: 773px">';
	foreach($links as $link) {
		echo '<a class="menu-link" href="' . $link['url'] . '">' . $link['name'] . '</a>';
	}
	echo '</td>
	<td class="text-center vertical-top" style="padding: 0px;">';
}
?>