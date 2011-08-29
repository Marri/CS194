<?php

class Message {
	private 
		$id,	$to_id, $from_id, $subject, $message, $time_sent, $is_read,$in_inbox, $in_outbox;
	public function __ToString(){
		return "from: ".$this->from_id." subject: ".$this->subject." Sent: ".$this->time_sent.".";
	}
	public function GetID(){
		return $this->id;
	}
	public function GetToID(){
		return $this->to_id;
	}
	public function GetFromID(){
		return $this->from_id;
	}
	public function GetSubject(){
		return $this->subject;
	}
	public function GetTimeSent(){
		return $this->time_sent;
	}
public function GetText() { return $this->message; }

	public static function GetMessage($id, $to_id, $from_id, $subject, $message, $time_sent, $is_read,$in_inbox, $in_outbox){
		$curr_message = new Message();
		$curr_message->id = $id;
		$curr_message->to_id = $to_id;
		$curr_message->from_id = $from_id;
		$curr_message->subject = $subject;
		$curr_message->message = $message;
		$curr_message->time_sent = $time_sent;
		$curr_message->is_sent = $is_read;
		$curr_message->in_inbox = $in_inbox;
		$curr_message->in_outbox = $in_outbox;
		return $curr_message;
	}
	public static function GetMessageFromID($id){
		$query = "SELECT * FROM messages WHERE message_id='".$id."';";
		$results = runDBQuery($query);
		while($messages = mysql_fetch_assoc($results)) {
			return Message::GetMessage($id, $messages['to_id'],$messages['from_id'], $messages['subject'], $messages['message'], $messages['time_sent'],  $messages['is_read'], $messages['in_inbox'],  $messages['in_outbox']);
		}
	}
	
	private static function getIDFromUsername($username){
		$query = "SELECT user_id FROM users WHERE username='".$username."';";
		$results = runDBQuery($query);
		$user_ids = mysql_fetch_assoc($results);
		return $user_ids['user_id'];
	}
	
	public static function SendMessage($from_id, $to_username, $subject, $message){
		$curr_date = date("Y/m/d H:i:s");
		$to_id = self::getIDFromUsername($to_username);
		if($to_id > 0){
			$query = "INSERT INTO messages (from_id, to_id, subject, message, time_sent) VALUES ('".$from_id."', '".$to_id."', '".$subject."', '".$message."', '".$curr_date."');";
			runDBQuery($query);
			return "message sent";
		}
		return "username invalid";
	}
	public static function GetUserMessages($userid){
		$message_list = array();
		$query = "SELECT * FROM messages WHERE to_id = '".$userid."' ORDER BY time_sent desc;";
		$results = runDBQuery($query);
		
		while($messages = mysql_fetch_assoc($results)) {
			$curr_message = self::GetMessage($messages['message_id'], $messages['to_id'],$messages['from_id'], $messages['subject'], $messages['message'], $messages['time_sent'],  $messages['is_read'], $messages['in_inbox'],  $messages['in_outbox']);
			array_push($message_list, $curr_message);
		}
		return $message_list;
	}
	public static function GetUserSentMessages($userid){
		$message_list = array();
		$query = "SELECT * FROM messages WHERE from_id = '".$userid."';";
		$results = runDBQuery($query);
		
		while($messages = mysql_fetch_assoc($results)) {
			$curr_message = self::GetMessage($userid, $messages['to_id'],$messages['from_id'], $messages['subject'], $messages['message'], $messages['time_sent'],  $messages['is_read'], $messages['in_inbox'],  $messages['in_outbox']);
			array_push($message_list, $curr_message);
		}
		return $message_list;
	}


}
?>