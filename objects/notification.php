<?php
class Notification{
	private
		$id,
		$user_id,
		$type,
		$unread;
	
	public function __construct($note){
		$this->id = $note['notification_id'];
		$this->user_id = $note['user_id'];
		$this->type = $note['notification_type'];
		$this->unread = $note['unread'];
	}
	public function getID(){ return $this->id; }
	public function getUserID(){ return $this->user_id; }
	public function getNoteType(){ return $this->type; }
	public function unread(){ return $this->unread; }
	//probably need a notify function
	
	
}
?>
