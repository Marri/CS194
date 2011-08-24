<?php

$reg = true;

//Verify entries
if(!$username) {
	$errors[] = "You did not pick a username.";
	$reg = false;
}
if(!$lname) {
	$errors[] = "You did not pick a login name.";
	$reg = false;
}
if(!$email) {
	$errors[] = "You did not enter your email address.";
	$reg = false;
}
if(!$pass) {
	$errors[] = "You did not pick a password.";
	$reg = false;
}
if(!$confirm) {
	$errors[] = "You did not confirm your password.";
	$reg = false;
}

//Verify agreement
if($agree != "Y") {
	$errors[] = "You did not agree to the Terms of Service.";
	$reg = false;
}

//Verify username
if(strip_tags($username)!=$username || strlen($username) > 100)
{
	$errors[]="You did not enter a valid username.";
	$reg=false;
}

//Verify passwords
if($pass != $confirm) {
	$errors[] = "Your passwords do not match.";
	$reg = false;
}
if($pass && strlen($pass) > 16)
{
	$errors[]="Your password is too long (3-16 characters).";
	$reg=false;
}
if($pass && strlen($pass) < 3)
{
	$errors[]="Your password is too short (3-16 characters).";
	$reg=false;
}
if(strpos($pass,"'") > -1 || strpos($pass,'"') > -1) {
	$errors[] = "Please do not use apostrophes or quotation marks in your password.";
	$reg = false;
}
$hash = sha1($pass);

//Verify login name
if($lname && (strlen(ereg_replace('[a-zA-Z0-9]+', "", $lname)) >0 || strlen($lname) > 100)) {
	$errors[]="You did not enter an alphanumeric login name.";
	$reg=false;
} else {
	$queryString = "SELECT * FROM `users` WHERE `loginname` = '$lname'";
	$query = mysql_query($queryString) or die("$queryString<br>" . mysql_error());
	if(mysql_num_rows($query) > 0) {
		$errors[] = "That login name is already in use. Please pick another.";
		$reg = false;
	}
}

//Verify referer's ID
if($referID && (strlen($referID) > 20 || !is_numeric($referID)) ) {
	$errors[] = "You did not enter a valid user ID for the person who referred you.";
	$reg = false;
} elseif($referID) {
	$queryString = "SELECT * FROM `users` WHERE `userid` = '$referID'";
	$query = mysql_query($queryString) or die("$queryString<br>" . mysql_error());
	if(mysql_num_rows($query) == 0) {
		$errors[] = "You entered a user ID for the person who referred you, but that user ID does not exist.";
		$reg = false;
	}
}

//Verify email
if($email && (strlen($email) < 7 || strlen($email) > 255 || strlen(ereg_replace('[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}', "", $email)) > 0) )
{
	$errors[] = "You did not enter a valid email address.";
	$reg = false;
} else {
	$queryString = "SELECT * FROM `users` WHERE `email` = '$email'";
	$query = mysql_query($queryString) or die("$queryString<br>" . mysql_error());
	if(mysql_num_rows($query) > 0) {
		$errors[] = "This email address already has a Squffies account.";
		$reg = false;
	}
}

//If registration fails
if(!$reg) {
	echo "<div class='errors'>Oops!
	<ul>";
	foreach($errors AS $error) {
		echo "<li>$error</li>";
	}
	echo "</ul></div>";
	
//If registration succeeds
} else {
	//Create strings
	$iden = newiden();
	$activate = newiden();
	$username = mysql_real_escape_string($username);
	
	//Create account
	$query="INSERT INTO `users` (`userid`, `email`, `username`, `loginname`, `hash`, `iden`, `activate`, `levelid`, `datejoined`) VALUES ('','$email','$username','$lname','$hash','$iden','$activate','6',now());";
	mysql_query("$query") or die("<div class='error'>Error creating account! Please contact us at support@squffies.com and let us know about the error.<br /><br />" . mysql_error() . "</div>");
	$newID = mysql_insert_id();

	//Email user activation link
	$subj="Thank you for registering at Squffies.com!";
	$message="Welcome to Squffies, ". stripslashes($username) . "!  We're thrilled to have you.  Some information for your records, first.\n\nLogin name: ". stripslashes($lname) . "\nPassword: $pass\n\nTo activate your account and start using the site, please click the following link:\n\n";
	$message.="http://www.squffies.com/activate.php?reg=$activate\n\nThanks,\n-The Squffies team";
	$headers="From:support@squffies.com";
	mail($email,$subj,$message,$headers);
	
	//Store refer notice
	$queryString = 'INSERT INTO log_referrals (`logid`, `referrerid`, `email`, `action`, `name`, `datereferred`, `referredid`, `datejoined`) VALUES ("", "' . $referID . '", "' . $email . '", "Join", "", "", "' . $newID . '", now());';
	mysql_query("$queryString") or die("<div class='error'>Error creating account! Please contact us at support@squffies.com and let us know about the error.<br /><br />" . mysql_error() . "</div>");	
	
	//Display message and quit
	echo "<div class='success'>Success! You have created a Squffies account.  Please check your email for an activation key to activate your account.</div>";
	include('./includes/footer.php');
	die();
}

//Generates unique 30 character string
function newiden(){
  $word='';
  for($i=0;$i<29;$i++){
    $which=rand(1,3);
    if($which==1){
      $let=rand(48,57);
    }
    if($which==2){
      $let=rand(65,90);
    }
    if($which==3){
      $let=rand(97,122);
    }
    $word.=chr($let);
  }
  return $word;
}
?>