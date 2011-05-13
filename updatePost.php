<?php
	include("./includes/header.php");
	
	
	$thread_id = $_GET["thread_id"];
	$post_id = $_GET["post_id"];
	$edited_text = $_GET["edited_text"];
	Post::UpdatePostText($post_id, $edited_text);
	echo $post;
?>

