<?php
include('../includes/connect.php');
include('../objects/squffy.php');

$query = 'SELECT * FROM `jobs_doctor` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
$result = runDBQuery($query);

$doctors = "";
while($info = @mysql_fetch_assoc($result)) {
	$id = $info['patient_id'];
	$patient = Squffy::getSquffyByID($id);
	
	//If a doctor, add to list to finish healing
	$id = $info['doctor_id'];
	if($id > 0) {
		$doctor = Squffy::getSquffyByID($id);
		$patient->heal($doctor);
		$doctors .= ', ' . $doctor->getID();
		continue;
	}

	$patient->heal();
}

$query = 'DELETE FROM `jobs_doctor` WHERE TO_DAYS(now()) - TO_DAYS(date_finished) >= 0';
runDBQuery($query);

if(strlen($doctors) > 0) {
	$doctors = substr($doctors, 2);
	$query = "UPDATE `squffies` SET `is_working` = 'false' WHERE squffy_id IN ($doctors)";
	runDBQuery($query);
}
?>