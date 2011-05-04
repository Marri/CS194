<?php
$selected = "home";
include("./includes/header.php");
$login = "";
$act_key = "";
$password = "";
if(isset($_POST['activate'])){
	$login = mysql_real_escape_string($_POST['login']);
	$act_key =  mysql_real_escape_string($_POST['act_key']);
	$password =  $_POST['password'];
	
	$user = User::getUserByLogin($login, $password);
	$act_error = "";
	if($user != NULL){
		$act_error = User::activateUser($user, $act_key);
	}else{
		$act_error = "Username or Password Wrong.";
	}
	if($act_error == ""){
		?>
		<h2>Activation Completed!</h2>
		<?php
	}else{
		?> 
		
		<h2><?php echo $act_error; ?></h2>
		<form class='text-center width100p' action="activate.php" method="post">
			<br><label id="loginLabel">Login Name: </label><input name="login" type="text" value="<?php echo $login; ?>" ></br>
			<br><label id="activateLabel">Activation Key: </label><input name="act_key" type="text" value="<?php echo $act_key; ?>"></br>
			<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
			<br><input name="activate" type="submit" value="Activate Account"></br>
		</form>
		<?php
	}

}else{
?>

<div class='text-center width100p'><h1>Activate Your Squffy Account!</h1></div>

<form class='text-center width100p' action="activate.php" method="post">
	<br><label id="loginLabel">Login Name: </label><input name="login" type="text" ></br>
	<br><label id="activateLabel">Activation Key: </label><input name="act_key" type="text"></br>
	<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
	<br><input name="activate" type="submit" value="Activate Account"></br>
</form>

<?php
}
include('./includes/footer.php');
?>