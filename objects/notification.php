<?php
class Notification{
	//type 1 is mating request

	protected
		$id,
		$user_id,
		$type,
		$unread,
		$sender, 
		$sent, 
		$user;
	
	public function __construct($note){
		$this->sender = $note['other_id'];
		$this->id = $note['notification_id'];
		$this->user_id = $note['user_id'];
		$this->type = $note['notification_type'];
		$this->unread = $note['unread'];
		$this->sent = $note['date_sent'];
	}
	public function getID(){ return $this->id; }
	public function getUserID(){ return $this->user_id; }
	public function getUser() {
		if($this->user != NULL) { return $this->user; }
		$this->user = User::getUserByID($this->sender);
		return $this->user;
	}	
	public function getUserLink() {
		$user = $this->getUser();
		if($user == NULL) { return ""; }
		return $user->getLink();
	}
	public function getNoteType(){ return $this->type; }
	public function unread(){ return $this->unread == 'true'; }
	public function getType() { return "Unknown"; }
	public function getSent() { return $this->sent; }
	
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
		$type = $note->getNoteType();
		switch($type) {
			case MatingNotification::TYPE: return new MatingNotification($info);
			default: return $note;
		}
	}
}

class MatingNotification extends Notification {
	const TYPE = 1;
	
	private 
		$sender_squffy, 
		$recipient_squffy;
		
	public function __construct($note){
		$this->sender_squffy = $note['other_squffy'];
		$this->recipient_squffy = $note['your_squffy'];
		parent::__construct($note);
		$this->user = NULL;
	}
	
	public function getType() { return "Squffy mate request"; }
	public function getRequestedSquffy() { return $this->recipient_squffy; }
	public function getSentSquffy() { return $this->sender_squffy; }
	public function getLink() { return "view_squffy.php?id=".$this->recipient_squffy."&view=interact"; }
	
	public static function send($from, $other_id, $recip) {
		$to = $recip->getOwnerID();
		$id = $recip->getID();
		
		$query = "INSERT INTO notifications VALUES (NULL, 1, $to, 'true', $from, $other_id, $id, now())";
		runDBQuery($query);
	}
	
	public static function requestExists($squffy1, $squffy2) {
		$user1 = $squffy1->getOwnerID();
		$id1 = $squffy1->getID();
		$user2 = $squffy2->getOwnerID();
		$id2 = $squffy2->getID();
		
		$query = "SELECT * FROM notifications WHERE user_id = $user1 AND other_id = $user2 AND other_squffy = $id2 AND your_squffy = $id1";
		$result = runDBQuery($query);
		if(@mysql_num_rows($result) > 0) { return true; }
		
		$query = "SELECT * FROM notifications WHERE user_id = $user2 AND other_id = $user1 AND other_squffy = $id1 AND your_squffy = $id2";
		$result = runDBQuery($query);
		return @mysql_num_rows($result) > 0;
	}
	
	public static function deleteNotification($squffy1, $squffy2) {
		$user1 = $squffy1->getOwnerID();
		$id1 = $squffy1->getID();
		$user2 = $squffy2->getOwnerID();
		$id2 = $squffy2->getID();
		
		$query = "SELECT * FROM notifications WHERE user_id = $user1 AND other_id = $user2 AND other_squffy = $id2 AND your_squffy = $id1";
		$result = runDBQuery($query);
		
		$query = "SELECT * FROM notifications WHERE user_id = $user2 AND other_id = $user1 AND other_squffy = $id1 AND your_squffy = $id2";
		$result = runDBQuery($query);
	}
	
	public static function getRequests($id, $userid) {
		$type = self::TYPE;
		$query = "SELECT * FROM notifications WHERE notification_type = $type AND (other_squffy = $id OR your_squffy = $id)";
		$result = runDBQuery($query);
		
		$notices = array();
		while($info = @mysql_fetch_assoc($result)) {
			if($info['user_id'] != $userid && $info['other_id'] != $userid) { continue; }
			$notices[] = new MatingNotification($info);
		}
		return $notices;
	}
}
?>
