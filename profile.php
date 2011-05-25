<?php
$selected = "home";
include("./includes/header.php");
?>
<?php
if(!isset($_POST['migrate'])){ ?>
	<div class='text-center width100p'><h1>Profile</h1></div>
	<h1> Migrate Old Account </h1>
	<form action="" method="post">
		<br><label id="loginLabel">Login Name: </label><input name="login" type="text"></br>
		<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
		<br><input name="migrate" type="submit" value="Migrate Old Account" class="margin-top-small submit-input"></br>
	</form>
<?php }else{
		$migrated = $user->migrateAccount($_POST['login'], $_POST['password']);
		if($migrated){
		?>
			<h1> Old Account  Migrated! </h1>
<?php }else{
			?><h1> Migration Failed </h1> <?php
		}
include('./includes/footer.php');
?>