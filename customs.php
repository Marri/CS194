<?php
if(isset($_GET['view'])) {
	$view = $_GET['view'];
	if($view == "design") {
		include('./design.php');
		die();
	} elseif ($view == 'designs') {
	} elseif ($view == 'create') {
		include('./custom.php');
		die();
	}
}

$selected = "squffies";
include("./includes/header.php");
?>

<a href='design.php'>Design a custom squffy</a><br />
<a href='designs.php'>Your saved designs</a><br />
<a href='custom.php'>Create a custom squffy</a><br />

<?php
include('./includes/footer.php');
?>