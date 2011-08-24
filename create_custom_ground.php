<?php
if(!$ground) {
	$errors[] = 'You have already made your free ground squffy.';
} else {
	$designs = Design::GetUserDesigns($userid);
	$num = sizeof($designs);
	if($num < 1) {
		$errors[] = 'You do not have any designs saved! Create designs in the <a href="design.php">custom designer</a>.';
	} else {
		$item_info['species'] = 2;
		$item_info['num'] = 2;
		$pay_type = 'ground';
		
		include('./create_custom_basic.php');
		die();
	}
}	
?>