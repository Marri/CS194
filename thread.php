<?php
include("./includes/header.php");
include("./objects/forums.php");
$thread_id = "";
if(isset($_GET["thread_id"])){

	$thread_id = mysql_real_escape_string($_GET["thread_id"]); //remember to check for sql injection
}else{
	$thread_id = mysql_real_escape_string($_POST["thread_id"]);
}	

if(isset($_POST['newPost'])){
	$post_text = mysql_real_escape_string($_POST["post_text"]);
	$board_id = mysql_real_escape_string($_POST["board_id"]);
	$poster = User::getUserByID($userid);
	Post::CreatePost($poster_id, $poster->getUsername(), $board_id, $thread_id, $post_text);
}
if(isset($_POST['editPost'])){
	$post_id = mysql_real_escape_string($_POST["post_id"]);
	$edited_text = mysql_real_escape_string($_POST["edited_text"]);
	Post::UpdatePostText($post_id, $edited_text);
}

$thread = Thread::getThreadFromID($thread_id);

$thread->LoadPosts();
$posts = $thread->GetPostList();
$post_list_size = count($posts);

echo "<table border='1'><tr><th>Post </th><th>Posted By</th><th> Last edited </th></tr>";
for($i=0;$i<$post_list_size; $i++){
	$curr_post = $posts[$i];?>
	<tr>
		<td><?php echo $curr_post->GetPostText();?></td>
		<td><?php echo $curr_post->GetPosterName()?></td>
		<td><?php echo $curr_post->GetEditTime()?></td>
		<td>
			<form action="editPost.php" method="post">
				<input type="hidden" name="post_id" value="<?php echo $curr_post->GetID(); ?>"/>
				<input type="hidden" name="thread_id" value="<?php echo $thread->GetID(); ?>"/>
				<input type="submit" value="Edit" name="editPost"/>
			</form>
		</td>
	</tr>
<?php
}
echo "</table>";
?>
<br><h2> Create Post </h2></br>
<form action="" method="post">
<br>Text: </br><br><textarea name="post_text" rows="10" cols="40"></textarea></br>
<input type="hidden" name="board_id" value="<?php echo $thread->GetBoardID();?>"/>
<br><input type="submit" name="newPost" value="Post"/></br>
</form>

