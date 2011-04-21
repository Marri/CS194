<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	
	$thread_id = $_GET["thread_id"];
	$post_id = $_GET["post_id"];
	$edited_text = $_GET["edited_text"];
	Post::UpdatePostText($post_id, $edited_text);
	echo $post;
?>

<a href='http://squffies.com/dev/thread.php?thread_id=<?php echo $thread_id; ?> '> go back to thread</a>