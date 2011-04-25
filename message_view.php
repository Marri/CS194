<?php
include("./includes/header.php");
include("./objects/messaging.php");
$userid = "1"; //$_S
$message_list = array();

$query = "SELECT * FROM messages WHERE to_id = '".$userid."';";
$results = runDBQuery($query);

while($messages = mysql_fetch_assoc($results)) {
	$curr_message = Message::CreateMessage($id, $messages['to_id'],$messages['from_id'], $messages['message_type'], $messages['subject'], $message['message'], $message['time_sent'],  $message['is_read'], $message['in_inbox'],  $message['in_outbox']);
	array_push($message_list, $curr_message);
}

for($i = 0; $i < count($message_list); $i++){
	echo $message_list[$i];
}

?>
<h1> Message View</h1>
<?php

include('./includes/footer.php');
?>
