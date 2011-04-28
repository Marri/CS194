<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	$to_id = mysql_real_escape_string($_POST['to_id']);
	$to_username = mysql_real_escape_string($_POST['to_username']);
	$subject = mysql_real_escape_string($_POST['subject']);	
?>
<form action="messages.php" method="post">
	<br><label id="toLabel">To: </label><input name="to_username" type="text" value="<?php echo $to_username; ?>"/></br>
	<br><label id="subjectLabel">Subject: </label><input name="subject" type="text" value="<?php echo "RE:".$subject; ?>"/></br>
	<br><label id="messageLabel">Message: </label></br>
	<br><textarea name="message_text" rows="10" cols="40"></textarea></br>
	<br><input name="send" type="submit" value="send"></br>
</form>


<?php include("./includes/footer.php"); ?>