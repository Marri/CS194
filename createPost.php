<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	

	$post_text = $_GET["post_text"];
	$board_id = $_GET["board_id"];
	$poster_id = $_GET["poster_id"];
	$thread_id = $_GET["thread_id"];
	
		//echo $poster_id;
		$post = Post::CreatePost($poster_id, "Mr Dev Man", $board_id, $thread_id, $post_text);
	
?>

<a href='http://squffies.com/dev/thread.php?thread_id=<?php echo $thread_id; ?> '> go back to thread</a>