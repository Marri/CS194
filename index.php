<?php
$selected = "home";

include("./includes/header.php");
if($loggedin){
	echo "logged in";
}else{
	echo "logged out";
	include("./login.php");
}
?>

<div class='text-center width100p'><h1>Get Ready to Go Nuts!</h1></div>

<?php
include('./includes/footer.php');
?>