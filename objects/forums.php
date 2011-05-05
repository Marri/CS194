<?php


//Forum classes
class Post{
	private
		$id, $thread_id, $board_id, $poster_id, $poster_name, $post_time,$edit_time, $post_text;
	//private static function Update
	public static function UpdatePostText($post_id, $updated_text){
		$query = "UPDATE post_text SET post_text='".$updated_text."' WHERE post_id = '".$post_id."';";
		
		$result = runDBQuery($query);
		
		$curr_date = date("Y/m/d H:i:s");
		$query = "UPDATE posts SET edit_time='".$curr_date."' WHERE post_id = '".$post_id."';";
		$result = runDBQuery($query);
	}
	public static function getLastTenPosts($user_id){
		$query = "SELECT posts.post_id, posts.thread_id, posts.board_id, posts.poster_id, posts.poster_name, posts.post_time, posts.edit_time, post_text.post_text FROM posts INNER JOIN post_text ON posts.post_id=post_text.post_id WHERE posts.poster_id = '".$user_id."' LIMIT 0 , 10;";
		$results = runDBQuery($query);
		$post_array = array();
		while($posts = mysql_fetch_assoc($results)) {
			$curr_post = Post::CreatePostFromAttributes($posts['post_id'],$posts['thread_id'],$posts['board_id'], $posts['poster_id'], $posts['poster_name'],$posts['post_time'], $posts['edit_time'], $posts['post_text']);
			$post_array[] = $curr_post;
		}
		return $post_array;
	}
	public static function CreatePostFromAttributes($id, $thread_id, $board_id, $poster_id, $poster_name, $post_time,$edit_time, $post_text){
		$post = new Post();
		$post->id = $id;
		$post->thread_id = $thread_id;
		$post->board_id = $board_id;
		$post->poster_id = $poster_id;
		$post->poster_name = $poster_name;
		$post->post_time = $post_time;
		$post->edit_time = $edit_time;
		$post->post_text = $post_text;
				
		return $post;
	}
	public static function GetPostFromID($post_id){
		$query = "SELECT posts.post_id, posts.thread_id, posts.board_id, posts.poster_id, posts.poster_name, posts.post_time, posts.edit_time, post_text.post_text FROM posts INNER JOIN post_text ON posts.post_id=post_text.post_id WHERE posts.post_id = '".$post_id."'";
		$results = runDBQuery($query);
		while($posts = mysql_fetch_assoc($results)) {
			$curr_post = Post::CreatePostFromAttributes($posts['post_id'],$posts['thread_id'],$posts['board_id'], $posts['poster_id'], $posts['poster_name'],$posts['post_time'], $posts['edit_time'], $posts['post_text']);
			return $curr_post;
		}
	}
	public static function DeletePost($post_id){
		$query = "UPDATE posts SET deleted = 'true' WHERE post_id = '".$post_id."';";
		
		$result = runDBQuery($query);
	}
	public function __toString(){
		return $this->id.'  "'.$this->post_text.'" posted by '.$this->poster_name.' on '.$this->post_time.'.  last edited on '.$this->edit_time;
	}
	public function GetID(){
		return $this->id;
	}
	public function GetThreadID(){
		return $this->thread_id;
	}
	public function GetBoardID(){
		return $this->board-id;
	}
	public function GetPosterID(){
		return $this->poster_id;
	}
	public function GetPosterName(){
		return $this->poster_name;
	}
	public function GetPostTime(){
		return $this->post_time;
	}
	public function GetEditTime(){
		return $this->edit_time;
	}
	public function GetPostText(){
		return $this->post_text;
	}
	public static function CreatePost($poster_id, $poster_name, $board_id, $parent_thread, $post_text){
		$curr_date = date("Y/m/d H:i:s");
		
		$query = "INSERT INTO posts (thread_id, board_id, poster_name, poster_id, post_time, edit_time) VALUES ('".$parent_thread->GetID()."','".$board_id."','".$poster_name."', '".$poster_id."', '".$curr_date."', '".$curr_date."');";
		//echo $query;
		$result = runDBQuery($query);
		$post_id =  mysql_insert_id();
		
		
		$query = "INSERT INTO post_text (post_text, post_id) VALUES ('".$post_text."', '".$post_id."');";
		$result = runDBQuery($query);
		
		$numReplies = $parent_thread->GetNumReplies() + 1;
		$query = "UPDATE boards JOIN threads ON threads.board_id=boards.board_id SET boards.last_post_id='".$poster_id."', threads.time_updated='".$curr_date."', threads.num_replies='".$numReplies."' WHERE threads.thread_id='".$parent_thread->GetID()."';";
		$result = runDBQuery($query);
	}
	
}
class Thread{
	private
		$id, $board_id, $name, $poster_id, $thread_text, $time_posted, $time_updated, $num_replies, $locked, $sticky, $deleted, $post_list;
	public function GetPostList(){
		return $this->post_list;
	}
	public function LoadPosts(){
		$query = "SELECT posts.post_id, posts.thread_id, posts.board_id, posts.poster_id, posts.poster_name, posts.post_time, posts.edit_time, post_text.post_text FROM posts INNER JOIN post_text ON posts.post_id=post_text.post_id WHERE thread_id = '".$this->id."' ORDER BY posts.post_time";
		$results = runDBQuery($query);
		while($posts = mysql_fetch_assoc($results)) {
			$curr_post = Post::CreatePostFromAttributes($posts['post_id'],$posts['thread_id'],$posts['board_id'], $posts['poster_id'], $posts['poster_name'],$posts['post_time'], $posts['edit_time'], $posts['post_text']);
			array_push($this->post_list, $curr_post);
		}
	}
	public static function GetThreadFromID($thread_id){
		$query = "SELECT * FROM threads WHERE thread_id = '".$thread_id."'";
		//echo $query;
		$threadArr = runDBQuery($query);
		$curr_thread = new Thread();
		while($threads = mysql_fetch_assoc($threadArr)) {
			$curr_thread->setID($threads['thread_id']);
			$curr_thread->setBoardID($threads['board_id']);
			$curr_thread->setName($threads['thread_name']);
			$curr_thread->setPosterID($threads['poster_id']);
			$curr_thread->setThreadText($threads['thread_text']);
			$curr_thread->setTimePosted($threads['time_posted']);
			$curr_thread->setTimeUpdated($threads['time_updated']);
			$curr_thread->setNumReplies($threads['num_replies']);
			$curr_thread->setLock($threads['locked']);
			$curr_thread->setSticky($threads['sticky']);
			$curr_thread->setDeleted($threads['deleted']);
		}
		return $curr_thread;
	}
	/*
	*This inserts the new thread into the table
	*/
	public static function CreateThread($poster_id, $thread_name, $thread_text, $board_id, $sticky){
		$curr_date = date("Y/m/d H:i:s");
		$query = "INSERT INTO threads (board_id, thread_name, poster_id, thread_text, num_replies, sticky, time_posted, time_updated) VALUES ('".$board_id."','".$thread_name."', '".$poster_id."', '".$thread_text."', 0,".$sticky.", '".$curr_date."', '".$curr_date."');";
		$result = runDBQuery($query);
		$thread_id =  mysql_insert_id();
		$poster = User::getUserByID($poster_id);
		Post::CreatePost($poster_id, $poster->getUsername(), $board_id, $thread_id, $thread_text);
	}
	/*
	* This sets the delete column for the given thread to true
	*/
	public static function DeleteThread($thread_id){
		$query = "UPDATE threads SET deleted = 'true' WHERE thread_id = '".$thread_id."';";
		//echo $query;
		$result = runDBQuery($query);
	}
	public function __construct(){
		$this->post_list = array();
	}
	public function __toString(){
		return $this->id." thread ".$this->name." created by user ".$this->poster_id." says ".$this->thread_text." deleted = ".$this->deleted;
	}
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;
	}
	public function setBoardId($board_id){
		$this->board_id = $board_id;
	}	
	public function getBoardID(){
		return $this->board_id;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getName(){
		return $this->name;
	}
	public function setPosterID($poster_id){
		$this->poster_id = $poster_id;
	}
	public function getPosterID(){
		return $this->poster_id;
	}
	public function setThreadText($thread_text){
		$this->thread_text = $thread_text;
	}
	public function getThreadText(){
		return $this->thread_text;
	}
	public function setTimePosted($timePosted){
		$this->time_posted = $timePosted;
	}
	public function getTimePosted(){
		return $this->time_posted;
	}
	public function setTimeUpdated($timeUpdated){
		$this->time_updated = $timeUpdated;
	}
	public function getTimeUpdated(){
		return $this->time_updated;
	}
	public function setNumReplies($numReplies){
		$this->num_replies = $numReplies;
	}
	public function getNumReplies(){
		return $this->num_replies;
	}
	public function setLock($locked){
		$this->locked = $locked;
	}
	public function isLocked(){
		return $this->locked;
	}
	public function setSticky($sticky){
		$this->sticky = $sticky;
	}
	public function isSticky(){
		return $this->sticky;
	}
	public function setDeleted($deleted){
		$this->deleted = $deleted;
	}
	public function isDeleted(){
		return $this->deleted;
	}
}
class Board{
	private
		$id, $subforum_id, $name, $description, $order, $last_post_id, $num_threads, $num_posts, $auth_view, $anon_post, $thread_list;
	public function __construct(){
		$this->thread_list = array();
	}
	public function getThreads(){
		return $this->thread_list;
	}
	public function loadThreads(){
		$query = "SELECT * FROM `threads` WHERE board_id ='".$this->id."' ORDER BY time_updated;";
		$result = runDBQuery($query);
		$curr_thread = new Board();
		while($threads = mysql_fetch_assoc($result)) {
			$curr_thread = new Thread();
			$curr_thread->setID($threads['thread_id']);
			$curr_thread->setBoardID($threads['board_id']);
			$curr_thread->setName($threads['thread_name']);
			$curr_thread->setPosterID($threads['poster_id']);
			$curr_thread->setThreadText($threads['thread_text']);
			$curr_thread->setTimePosted($threads['time_posted']);
			$curr_thread->setTimeUpdated($threads['time_updated']);
			$curr_thread->setNumReplies($threads['num_replies']);
			$curr_thread->setLock($threads['locked']);
			$curr_thread->setSticky($threads['sticky']);
			$curr_thread->setDeleted($threads['deleted']);
			array_push($this->thread_list, $curr_thread);
		}
	}		
	public static function getBoardFromID($id){
		$query = "SELECT * FROM `boards` WHERE board_id ='".$id."';";
		$result = runDBQuery($query);
		$curr_board = new Board();
		while($boards = mysql_fetch_assoc($result)) {
			$curr_board->setID($boards['board_id']);
			$curr_board->setSubForumID($boards['subforum_id']);
			$curr_board->setName($boards['board_name']);
			$curr_board->setOrder($boards['board_order']);
			$curr_board->setDescription($boards['board_description']);
			$curr_board->setLastPostID($boards['last_post_id']);
			$curr_board->setNumThreads($boards['num_threads']);
			$curr_board->setNumPosts($boards['num_posts']);
			$curr_board->setAuthView($boards['auth_view']);
			$curr_board->setAnonPost($boards['anonymous_posting']);
		}
		return $curr_board;
	}
	public function setID($id){
		$this->id = $id;
	}
	public function getID(){
		return $this->id;
	}
	public function setSubForumID($subforum_id){
		$this->subforum_id = $subforum_id;
	}
	public function setName($name){
		$this->name = $name;
	}
	
	public function getName(){ return $this->name;}
	
	public function setDescription($description){
		$this->description = $description;
	}
	public function getDescription(){ return $this->description;}

	public function setOrder($order){
		$this->order = $order;
	}
	public function getOrder(){ return $this->order;}
	

	public function setLastPostID($lastPostID){
		$this->last_post_id = $lastPostID;
	}
	public function getLastPostID(){ return $this->last_post_id;}


	public function setNumThreads($numThreads){
		$this->num_threads = $numThreads;
	}
	public function getNumThreads(){ return $this->numThreads;}


	public function setNumPosts($numPosts){
		$this->num_posts = $numPosts;
	}
	public function getNumPosts(){ return $this->num_posts;}


	public function setAuthView($authView){
		$this->auth_view = $authView;
	}
	public function getAuthView(){ return $this->auth_view;}


	public function setAnonPost($anonPost){
		//make sure php casts this correctly
		$this->anon_post = $anonPost;
	}
	public function canAnonPost(){
		return $this->anon_post;
	}
	public function __toString(){
		return "The ".$this->name." Board is being debugged.";
	}
}
class SubForum {
	private
		$name, $id, $order, $board_list;
	public function __construct() {
       		//parent::__construct();
		$this->board_list = array();
   	}
	public function getBoardList(){
		return $this->board_list;
	}
	public function setSubForum($subforum_id, $subforum_name, $subforum_order){
		$this->name = $subforum_name;
		$this->order = $subforum_order;
		$this->id = $subforum_id;		
	}
	public function getName() { return $this->name; }
		
	public function loadBoards(){
		$query = "SELECT * FROM `boards` WHERE subforum_id ='".$this->id."' ORDER BY board_order;";
		$result = runDBQuery($query);
		while($boards = mysql_fetch_assoc($result)) {
			$curr_board = new Board();
			$curr_board->setID($boards['board_id']);
			$curr_board->setSubForumID($boards['subforum_id']);
			$curr_board->setName($boards['board_name']);
			$curr_board->setOrder($boards['board_order']);
			$curr_board->setDescription($boards['board_description']);
			$curr_board->setLastPostID($boards['last_post_id']);
			$curr_board->setNumThreads($boards['num_threads']);
			$curr_board->setNumPosts($boards['num_posts']);
			$curr_board->setAuthView($boards['auth_view']);
			$curr_board->setAnonPost($boards['anonymous_posting']);
			array_push($this->board_list, $curr_board);
		}
	}
}
class Forum{
	private 
		$subforum_list;
	public function __construct() {
       		//parent::__construct();
		$this->subforum_list = array();
   	}
	
	public function loadSubForums(){
		$query = "SELECT * FROM `subforums` ORDER BY subforum_order;";
		$result = runDBQuery($query);
		while($subforums = mysql_fetch_assoc($result)) {
			$currSBFrm = new SubForum();
			$currSBFrm->setSubForum($subforums['subforum_id'], $subforums['subforum_name'],$subforums['subforum_order']);
			$currSBFrm->loadBoards();
			array_push($this->subforum_list, $currSBFrm);
		}
	}
	
	public function getSubForums(){
		return $this->subforum_list;
	}
	
}
?>
