<?php
$selected = 'interact';
	include("./includes/header.php");
?>

<h1>Recent Posts</h1>

<?php
	
	$posts = Post::getLastTenPosts($userid);
	$post_list_size = sizeof($posts);
	
echo "<table border='1'><tr><th>Post </th><th> Last edited </th></tr>";
for($i=0; $i<$post_list_size; $i++){
	$curr_post = $posts[$i];?>
	<tr>
		<td><?php echo $curr_post->GetPostText();?></td>
		<td><?php echo $curr_post->GetEditTime()?></td>
	</tr>
<?php }
echo "</table>";
?>
<?php include("./includes/footer.php"); ?>