<?php
$selected = "account";
include("./includes/header.php");

$cur = "odd";
$user_name = '';
$login_name = '';
$referer = isset($_GET['refer']) ? $_GET['refer'] : '';

if(isset($_POST['register'])){
	include('./scripts/process_register.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="register.php" method="post">
<table class="width100p" cellspacing="0"><tr><th class="content-header" colspan="2">Register</th></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Username </label></th><td><input name="username" type="text" value="<?php echo $user_name; ?>"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="loginLabel">Login Name </label></th><td><input name="login" type="text" value="<?php echo $login_name; ?>"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="emailLabel">Email Address </label></th><td><input name="email" type="text"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="passwordLabel">Password </label></th><td><input name="password" type="password"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="confirmLabel">Confirm Password </label></th><td><input name="confirm" type="password"></td></tr>
        <tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="referLabel">Referred By* </label></th><td><input name="referer" type="text" value="<?php echo $referer; ?>"></td></tr>
        <tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"></th><td class="small vertical-top pad-bottom-small"><b>*Optional</b> You may enter either the username or ID of the person who told you about Squffies.</td></tr>
        <tr<?php $cur = row($cur); ?>><td></td><td><input type="checkbox" name="agree" value="true" <?php echo isset($_POST['agree']) ? 'checked' : ''; ?> /> I understand and agree to abide by the <a href="#">Terms of Service</a> and the <a href="#">Privacy Policy</a>.</td></tr>
		<tr<?php $cur = row($cur); ?>><th colspan="2"><input class="submit-input" name="register" type="submit" value="Register"></td></tr>
</table>      
	</form>

<?php /*

if(isset($_POST['register'])){
	$username = mysql_real_escape_string($_POST['username']);
	$loginname = mysql_real_escape_string($_POST['login']);
	$password = $_POST['password'];
	$confirm_pass = $_POST['confirm'];
	$email = mysql_real_escape_string($_POST['email']);
	
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
<?php	*/
include("./includes/footer.php");		
?>
