<?php
include("./includes/header.php");


$username = "";
$loginname = "";
$password = "";

$password_error = "";
$username_error = "";
$login_error = "";
$confirm_error = "";

if(isset($_POST['register'])){
	$username = mysql_real_escape_string($_POST['username']);
	$loginname = mysql_real_escape_string($_POST['login']);
	$password = $_POST['password'];
	$confirm_pass = $_POST['confirm'];
	
	$canRegister = true;
	if(User::usernameTaken($username)){ 
		$canRegister = false;
		$username_error = "Username already taken.";
	}
	if(User::loginNameTaken($loginname)){ 
		$canRegister = false;
		$login_error = "login_name already taken";
	}
	$password_error = User::passwordValid($password);
	if($password_error != ""){ 
		$canRegister = false;
	}
	if($password != $confirm_pass){
		$confirm_error = "Password and Confirm fields don't match.";
	}
	
	if($canRegister){
		User::createNewUser($username, $password, $loginname);
		?><h1> Registration Successful!</h1>
		
	 <?php }else{ ?>
		<h1> Register! </h1>
		<form action="register.php" method="post">
			<br><label id="userLabel">New Username: </label><input name="username" type="text" value="<?php echo $username; ?>"><?php echo $username_error; ?></br>
			<br><label id="loginLabel">New Login Name: </label><input name="login" type="text" value="<?php echo $loginname; ?>"><?php echo $login_error; ?></br>
			<br><label id="passwordLabel">New Password: </label><input name="password" type="password"><?php echo $password_error; ?></br>
			<br><label id="confirmLabel">Confirm Password: </label><input name="confirm" type="password"><?php echo $confirm_error; ?></br>
			<br><input name="register" type="submit" value="Register"></br>
		</form><?php
	}

}else{?>
	<h1> Register! </h1>
	<form action="register.php" method="post">
		<br><label id="userLabel">New Username: </label><input name="username" type="text"></br>
		<br><label id="loginLabel">New Login Name: </label><input name="login" type="text"></br>
		<br><label id="passwordLabel">New Password: </label><input name="password" type="password"></br>
		<br><label id="confirmLabel">Confirm Password: </label><input name="confirm" type="password"></br>
		<br><input name="register" type="submit" value="Register"></br>
	</form>
<?php }
?>
<?php	
include("./includes/footer.php");		
?>
