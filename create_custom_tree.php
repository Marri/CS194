<?php
if(!$tree) {
	$errors[] = 'You have already made your free tree squffy.';
} else {
	$designs = Design::GetUserDesigns($userid);
	$num = sizeof($designs);
	if($num < 1) {
		$errors[] = 'You do not have any designs saved! Create designs in the <a href="design.php">custom designer</a>.';
	} else {
		$item_info['species'] = 1;
		$item_info['num'] = 2;
		$pay_type = 'tree';
		
		include('./create_custom_basic.php');
		die();
	}
}	
?>