<?php

class Message {
	private 
		$id,	$to_id, $from_id, $message_type, $subject,message, $time_sent, $is_read,$in_inbox, $in_outbox;
	public static function CreateMessage($id,	$to_id, $from_id, $message_type, $subject,message, $time_sent, $is_read,$in_inbox, $in_outbox){
		$curr_message = new Message();
		$curr_message->id = $id;
		$curr_message->to_id = $messages['to_id'];
		$curr_message->from_id = $messages['from_id'];
		$curr_message->message_type = $messages['message_type'];
		$curr_message->subject = $messages['subject'];
		$curr_message->message = $message['message'];
		$curr_message->time_sent = $message['time_sent'];
		$curr_message->is_sent = $message['is_read'];
		$curr_message->in_inbox = $message['in_inbox'];
		$curr_message->in_outbox = $message['in_outbox'];
		return $curr_message;
	}
	public static function GetMessageFromID($id){
		$query = "SELECT * FROM messages WHERE message_id='".$id."';"
		$results = runDBQuery($query);
		while($messages = mysql_fetch_assoc($results)) {
			return Message::CreateMessage($id, $messages['to_id'],$messages['from_id'], $messages['message_type'], $messages['subject'], $message['message'], $message['time_sent'],  $message['is_read'], $message['in_inbox'],  $message['in_outbox']);
		}
	}
}
?>