<?php
//Every fifteen minutes
include('../includes/connect.php');
include('../objects/squffy.php');
include('../objects/personality.php');
include('../objects/appearance.php');
include('../objects/cost.php');

//Reset breeding rights
$query = 'SELECT * FROM `squffies` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(breeding_rights_revert) >= 0';
$squffies = Squffy::getSquffies($query);
$in = '';
foreach($squffies as $squffy) {
	$in .= ', ' . $squffy->getID();
}
if(strlen($in) > 0) {
	$query = 'UPDATE squffies SET breeding_rights = NULL, breeding_rights_revert = NULL WHERE squffy_id IN (' . substr($in, 2) . ')';
	runDBQuery($query);
}

//Reset hire rights
$query = 'SELECT * FROM `squffies` WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(hire_rights_revert) >= 0';
$squffies = Squffy::getSquffies($query);
$in = '';
foreach($squffies as $squffy) {
	$in .= ', ' . $squffy->getID();
}
if(strlen($in) > 0) {
	$query = 'UPDATE squffies SET hire_rights = NULL, hire_rights_revert = NULL WHERE squffy_id IN (' . substr($in, 2) . ')';
	runDBQuery($query);
}
?>