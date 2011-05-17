<?php
$selected = "home";
include("./includes/header.php");
?>

<div class='text-center width100p'><h1>Profile</h1></div>
<h1> Migrate Old Account </h1>
	<form action="register.php" method="post">
		<br><label id="loginLabel">Login Name: </label><input name="login" type="text"></br>
		<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
		<br><input name="register" type="submit" value="Register"></br>
	</form>

<?php
include('./includes/footer.php');
?>