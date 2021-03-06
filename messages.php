<?php
$selected = 'interact';
$js[] = "messages";
include("./includes/header.php");
?>
<h1> Messages</h1>

<?php
if($loggedin){
	if(isset($_GET['id'])) {
		$id = getID('id');
		if(!Verify::VerifyID($id)) {
			displayErrors(array("That message does not exist."));
			include('./includes/footer.php');
			die();
		}
		$message = Message::GetMessageFromID($id);
		if($message->GetToID() != $userid && $message->GetFromID() != $userid) {
			displayErrors(array("That message was not sent to or by you."));
			include('./includes/footer.php');
			die();
		}

		echo '<b>Subject</b> '.$message->GetSubject();
		$sender = User::getUserByID($message->GetFromID());
		echo '<br /><b>Sent by</b>: ' . $sender->getLink();
		echo '<br /><b>Sent at</b>: ' . date("g:i a n/j/Y", strtotime($message->GetTimeSent()));
		echo '<br /><br />' . $message->GetText(); 

		$query = "UPDATE messages SET is_read = 'true' WHERE message_id = $id AND to_id = $userid";
		runDBQuery($query);

		include('./includes/footer.php');
		die();
	}

	if(isset($_POST['send'])){
		
		$to_username = mysql_real_escape_string($_POST['to_username']);
		$subject = mysql_real_escape_string($_POST['subject']);
		$message_text = mysql_real_escape_string($_POST['message_text']);
		echo Message::SendMessage($userid, $to_username, $subject, $message_text);
	}
	$message_list = Message::GetUserMessages($userid);
	
	echo "<table border='1'><tr><th>Subject </th><th>From </th><th> Sent </th></tr>";
	for($i = 0; $i < sizeof($message_list); $i++){
		$curr_message = $message_list[$i];
		$sender = User::getUserByID($curr_message->GetFromID());

		?>
		
		<tr>
			<td><a href="messages.php?id=<?php echo $curr_message->GetID()?>"><?php echo $curr_message->GetSubject();?></a></td>
			<td><?php echo $sender->getLink()?></td>
			<td><?php echo $curr_message->GetTimeSent()?></td>
<td>
				<form action="reply.php" method="post">
					<input type="hidden" name="to_id" value="<?php echo $sender->getID(); ?>">
					<input type="hidden" name="to_username" value="<?php echo $sender->getUsername(); ?>">
					<input name="subject" type="hidden" value="<?php echo $curr_message->GetSubject(); ?>">
					<input name="reply" type="submit" value="Reply">
				</form>
			</td>
		</tr>

		
		<?php 
	}
	echo "</table>";
	
?>
	<h2>Sent Messages</h2>
<?php
	$sent_list = Message::GetUserSentMessages($userid);
	
	echo "<table border='1'><tr><th>Subject </th><th>To </th><th> Sent </th></tr>";
	for($i = 0; $i < sizeof($sent_list); $i++){
		$curr_message = $sent_list[$i];
		$sender = User::getUserByID($curr_message->GetToID());

		?>
		
		<tr>
			<td><?php echo $curr_message->GetSubject();?></td>
			<td><?php echo $sender->getUsername()?></td>
			<td><?php echo $curr_message->GetTimeSent()?></td>
			<td>
				<form action="reply.php" method="post">
					<input type="hidden" name="to_id" value="<?php echo $curr_message->GetFromID(); ?>">
					<input type="hidden" name="to_username" value="<?php echo $sender->getUsername(); ?>">
					<input name="subject" type="hidden" value="<?php echo $curr_message->GetSubject(); ?>"/>
					<input name="reply" type="submit" value="Reply">
				</form>
			</td>
		</tr>

		
		<?php 
	}
	echo "</table>";	
?>
	<h2> Create New Message</h2>
	<form action="" method="post">
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
