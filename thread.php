<?php
include("./includes/header.php");
include("./objects/forums.php");
$thread_id = $_GET["thread_id"]; //remember to check for sql injection

$thread = Thread::getThreadFromID($thread_id);
echo "<br>".$thread."</br>";
$thread->LoadPosts();
$posts = $thread->GetPostList();
$post_list_size = count($posts);
	echo " there are ".$post_list_size." posts";
	
	for($i=0;$i<$post_list_size; $i++){
		$curr_post = $posts[$i];
		echo "<br>".$curr_post."</br>";
	}

?>
<br><h> create post </h></br>
<form action="createPost.php" method="get">
<br>Text: </br><br><textarea name="post_text" rows="10" cols="40"></textarea></br>
<input type="hidden" name="board_id" value="<?php echo $thread->GetBoardID();?>"/>
<input type="hidden" name="poster_id" value="45"/>
<input type="hidden" name="thread_id" value="<?php echo $thread->GetID(); ?>"/>
<br><input type="submit" /></br>
</form>
<br><h> delete post </h></br>
<form action="deletePost.php" method="get">
<br>Post ID: <input type="text" name="post_id" /></br>
<input type="hidden" name="thread_id" value="<?php echo $thread->GetID(); ?>"/>
<br><input type="submit" /></br>
</form>
<br><h> edit post </h></br>
<form action="editPost.php" method="get">
<br>Post ID: <input type="text" name="post_id" /></br>
<input type="hidden" name="thread_id" value="<?php echo $thread->GetID(); ?>"/>
<br><input type="submit" /></br>
</form>