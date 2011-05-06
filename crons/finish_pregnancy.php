<?php
include('../includes/connect.php');
include('../objects/squffy.php');
include('../objects/personality.php');

$query = 'SELECT * FROM `pregnancies` WHERE TO_DAYS(now()) - TO_DAYS(date_birth) >= 0';
$result = runDBQuery($query);
$mothers = '';

while($info = @mysql_fetch_assoc($result)) {
	$id = $info['mother_id'];
	$mother = Squffy::getSquffyByID($id);
	$id = $info['father_id'];
	$father = Squffy::getSquffyByID($id);
	$owner = $info['user_id'];
	Squffy::createChild($mother, $father, $owner);
	$mothers .= ', ' . $mother->getID();
}

$query = 'DELETE FROM `pregnancies` WHERE TO_DAYS(now()) - TO_DAYS(date_birth) >= 0';
echo $query."<br>";
//runDBQuery($query);

if(strlen($mothers) > 0) {
	$query = "UPDATE `squffies` SET `is_pregnant` = 'false' WHERE `squffy_id` IN (" . substr($mothers, 2) . ')';
	echo $query;
	//runDBQuery($query);
}
?>