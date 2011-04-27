<?php
	include("./includes/header.php");
	include("./objects/forums.php");
	
	
	$thread_id = mysql_real_escape_string($_POST["thread_id"]);
	$post_id = mysql_real_escape_string($_POST["post_id"]);
	$post = Post::GetPostFromID($post_id);
?>
<form action="thread.php" method="post">
<br>Text: </br><br><textarea name="edited_text" rows="10" cols="40"> <?php echo $post->GetPostText();?></textarea></br>
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>"/>
<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>"/>
<br><input type="submit" name="editPost"/></br>
</form>

<a href='./thread.php?thread_id=<?php echo $thread_id; ?> '> go back to thread</a>

<?php include(".includes/footer.php"); ?>