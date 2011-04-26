<?php
include("./includes/header.php");
include("./objects/messaging.php");
?>
<h1> Messages</h1>
<?php
if($loggedin){
	if(isset($_POST['send'])){
		
		$to_username = mysql_real_escape_string($_POST['to_username']);
		$subject = mysql_real_escape_string($_POST['subject']);
		$message_text = mysql_real_escape_string($_POST['message_text']);
		echo Message::SendMessage($userid, $to_username, $subject, $message_text);
	}
	$message_list = array();
	
	$query = "SELECT * FROM messages WHERE to_id = '".$userid."';";
	$results = runDBQuery($query);
	
	while($messages = mysql_fetch_assoc($results)) {
		$curr_message = Message::GetMessage($userid, $messages['to_id'],$messages['from_id'], $messages['subject'], $messages['message'], $messages['time_sent'],  $messages['is_read'], $messages['in_inbox'],  $messages['in_outbox']);
		array_push($message_list, $curr_message);
	}
	
	for($i = 0; $i < count($message_list); $i++){
		echo $message_list[$i];
	}


?>
	
	<h2> Create New Message</h2>
	<form action="messages.php" method="post">
		<br><label id="toLabel">To: </label><input name="to_username" type="text"/></br>
		<br><label id="subjectLabel">Subject: </label><input name="subject" type="text"/></br>
		<br><label id="messageLabel">Message: </label></br>
		<br><textarea name="message_text" rows="10" cols="40"></textarea></br>
		<br><input name="send" type="submit" value="send"></br>
	</form>
<?php }else{ ?>
	<h1>Please Log in</h1>
<?php }


?>

<?php

include('./includes/footer.php');
?>
