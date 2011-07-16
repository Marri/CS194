<?php
$valid = true;

$name = $_POST['plot_name'];
if(!$name) {
	$valid = false;
	$errors[] = "You did not enter a name for your new farm plot.";
}

$type = $_POST['type'];


if($valid) {
	echo 'bought';
}
?>