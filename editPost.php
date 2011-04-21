<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	
	$thread_id = $_GET["thread_id"];
	$post_id = $_GET["post_id"];
	$post = Post::GetPostFromID($post_id);
	echo $post;
?>
<form action="updatePost.php" method="get">
<br>Text: </br><br><textarea name="edited_text" rows="10" cols="40"> <?php echo $post->GetPostText();?></textarea></br>
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>"/>
<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>"/>
<br><input type="submit" /></br>
</form>

<a href='http://squffies.com/dev/thread.php?thread_id=<?php echo $thread_id; ?> '> go back to thread</a>