<?php
//Resend activation link
if($_POST['resend']) {
	include("./scripts/process_resendemail.php");
}

//Fix email address
if($_POST['fix']) {
	include("./scripts/process_fixemail.php");
}

$select = "account";
$sel = "activate";
$fornewbies = true;
include("./includes/header.php");

$iden = $_GET['reg'];

//Require activation key
if(!$iden || $iden=='')
{		
	//Ask for registration key
	echo '<form action="activate.php" method="get">
	<table class="content-table" cellspacing="4">
	<tr>
	<th colspan="2" class="content-header">Activate Your Squffies Account</th>
	</tr>
	<tr>
	<th class="content-subheader">Activation Key</th><td class="width400"><input class="width100p" name="reg" type="text" maxlength="30" /></td>
	</tr>
	<tr>
	<th colspan="2">
	<input type="submit" class="submit-input" value="Activate your Squffies account!" /></form><br /><br />
	</td>
	</tr>';
	
	//Resend information
	echo '<form action="activate.php" method="post">
	<tr>
	<th colspan="2" class="content-header">Didn\'t Get Our Email?</th>
	</tr>
	<tr>
	<th class="content-subheader">Email Address</th><td><input class="width100p" name="email" type="text" maxlength="255" /></td>
	</tr>
	<tr>
	<th colspan="2">
	<input type="submit" class="submit-input" name="resend" value="Resend activation link" /></form><br /><br />
	</td>
	</tr>';
	
	//Resend information
	echo '<form action="activate.php" method="post">
	<tr>
	<th colspan="2" class="content-header">Register with the Wrong Email?</th>
	</tr>
	<tr>
	<th class="content-subheader">Login Name</th><td><input class="width100p" name="loginname" type="text" maxlength="255" /></td>
	</tr>
	<tr>
	<th class="content-subheader">Password</th><td><input class="width100p" name="password" type="password" maxlength="255" /></td>
	</tr>
	<tr>
	<th class="content-subheader">New Email Address</th><td><input class="width100p" name="email" type="text" maxlength="255" /></td>
	</tr>
	<tr>
	<th colspan="2">
	<input type="submit" class="submit-input" name="fix" value="Update email address" />
	</td>
	</tr>
	</table></form>';
	
	include('./includes/footer.php');
	die();
}

//Remove tags and insertions from activation key and make sure is 30 characters
if(strlen($iden) != 29 || strlen(ereg_replace('[a-zA-Z0-9]{29}', "", $iden)) > 0)
{
	echo '<div class="errors">You need a valid activation key to activate your account!</div>';
	include('./includes/footer.php');
	die();
}

//Make sure an account with that activation key exists
$queryString = "SELECT * FROM `users` WHERE `activate` = '$iden'";
$query = @mysql_query("$queryString") or die('<div class="errors">Error activating account!</div>');
if(mysql_num_rows($query) == 0)
{
	echo '<div class="errors">No account with that activation key exists.</div>';
	include('./includes/footer.php');
	die();
}

//Activate account
$query="UPDATE `users` SET `levelid` = '5' WHERE `activate` = '$iden'";
@mysql_query("$query") or die('<span class="errors">Error activating account! Please contact support@squffies.com and let them know about the error.<br /><br />' . mysql_error() . '</div>');
echo '<div class="success">Congratulations! Your account has been activated. You can now log in to Squffies.</div>';

include('./includes/footer.php');
?>