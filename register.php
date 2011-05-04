<?php
include("./includes/header.php");


$username = "";
$loginname = "";
$password = "";
$email = "";

$password_error = "";
$username_error = "";
$login_error = "";
$confirm_error = "";
$email_error = "";

if(isset($_POST['register'])){
	$username = mysql_real_escape_string($_POST['username']);
	$loginname = mysql_real_escape_string($_POST['login']);
	$password = $_POST['password'];
	$confirm_pass = $_POST['confirm'];
	$email = mysql_real_escape_string($_POST['email']);
	
	$canRegister = true;
	if(User::usernameTaken($username)){ 
		$canRegister = false;
		$username_error = "Username already taken.";
		$username = "";
	}
	if(User::loginNameTaken($loginname)){ 
		$canRegister = false;
		$login_error = "login_name already taken";
		$loginname = "";
	}
	$password_error = User::passwordValid($password);
	if($password_error != ""){ 
		$canRegister = false;
	}
	if($password != $confirm_pass){
		$canRegister = false;
		$confirm_error = "Password and Confirm fields don't match.";
	}
	
	if(User::emailAddressInvalid($email)){
		$canRegister = false;
		$email_error = "Invalid Email Address";
	}
	if($canRegister){
		$user_id = User::createNewUser($username, $password, $loginname, $email);
		User::sendActivationKey($user_id, $email);
		?><h1> Registration Successful!</h1>
		<p>An Email Was sent to your account with your activation key!</p>
	 <?php }else{ ?>
		<h1> Register! </h1>
		<form action="register.php" method="post">
			<br><label id="userLabel">Username: </label><input name="username" type="text" value="<?php echo $username; ?>"><?php echo $username_error; ?></br>
			<br><label id="loginLabel">Login Name: </label><input name="login" type="text" value="<?php echo $loginname; ?>"><?php echo $login_error; ?></br>
			<br><label id="emailLabel">Email Address: </label><input name="email" type="text" value="<?php echo $email; ?>"> <?php echo $email_error; ?></br>
			<br><label id="passwordLabel">Password: </label><input name="password" type="password"><?php echo $password_error; ?></br>
			<br><label id="confirmLabel">Confirm Password: </label><input name="confirm" type="password"><?php echo $confirm_error; ?></br>
			<br><input name="register" type="submit" value="Register"></br>
		</form><?php
	}

}else{?>
	<h1> Register! </h1>
	<form action="register.php" method="post">
		<br><label id="userLabel">Username: </label><input name="username" type="text"></br>
		<br><label id="loginLabel">Login Name: </label><input name="login" type="text"></br>
		<br><label id="emailLabel">Email Address: </label><input name="email" type="text"></br>
		<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
		<br><label id="confirmLabel">Confirm Password: </label><input name="confirm" type="password"></br>
		<br><input name="register" type="submit" value="Register"></br>
	</form>
<?php }
?>
<?php	
include("./includes/footer.php");		
?>
