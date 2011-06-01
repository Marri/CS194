<?php
class Notification{
	//type 1 is mating request

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
	public function unread(){ return $this->unread == 'true'; }
	public function getType() { return "Unknown"; }
	
	public static function getNotificationsByUser($userid) {
		$notification_list = array();		
		$queryString = "SELECT * FROM notifications WHERE user_id = '".$userid."';";
		$results = runDBQuery($queryString);
		while($notes = @mysql_fetch_assoc($results)){
			$note = self::makeNotification($notes);
			array_push($notification_list, $note);
		}
		return $notification_list;
	}	
	
	private static function makeNotification($info) {
		$note = new Notification($info);
		if($note->getNoteType() == 1) { return new MatingNotification($info); }
		return $note;
	}
}

class MatingNotification extends Notification {
	private $sender, $sender_squffy, $recipient_squffy, $user;
		
	public function __construct($note){
		$this->sender = $note['other_id'];
		$this->sender_squffy = $note['other_squffy'];
		$this->recipient_squffy = $note['your_squffy'];
		parent::__construct($note);
		$this->user = NULL;
	}
	
	public function getUser() {
		if($this->user != NULL) { return $this->user; }
		$this->user = User::getUserByID($this->sender);
		return $this->user;
	}
	
	public function getType() { return "Mate request from " . $this->getUser()->getLink(); }
	
	public static function send($from, $other_id, $recip) {
		$to = $recip->getOwnerID();
		$id = $recip->getID();
		$query = "INSERT INTO notifications VALUES (NULL, 1, $to, 'true', $from, $other_id, $id)";
		runDBQuery($query);
	}
}
?>
