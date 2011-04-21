<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	
	$thread_name = $_GET["thread_name"];
	$thread_text = $_GET["thread_text"];
	$board_id = $_GET["board_id"];
	$sticky = $_GET["sticky"];
	$poster_id = $_GET["poster_id"];
	if($thread_name == "" || $thread_text == "" || $board_id == "" || $poster_id == ""){
		echo "Define Fail!";
	}else{
		//echo $poster_id;
		if(!isset($sticky)) $sticky = 'false';
		$thread = Thread::CreateThread($poster_id,$thread_name, $thread_text, $board_id, $sticky);
	}
?>

<a href='http://squffies.com/dev/board.php?board_id=<?php echo $board_id; ?> '> go back to board</a>