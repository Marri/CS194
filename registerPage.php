<?php
include("./includes/header.php");
?>

<form action="register.php" method="post">
	<br><label id="usernameText">Username: </label><input name="username" type="text"></br>
	<br><label id="loginNameText">Username: </label><input name="loginname" type="text"></br>
	<br><label id="passwordText">Password: </label><input name="password" type="text"><br>
	<input name="submitAccount" type="submit" value="submit">
</form>

<?php
include('./includes/footer.php');
?>