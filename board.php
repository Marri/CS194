<?php
include("./includes/header.php");
include("./objects/forums.php");


$board_id = mysql_real_escape_string($_GET["board_id"]);
if(isset($_POST['postThread'])){
	$thread_name = mysql_real_escape_string($_POST["thread_name"]);
	$thread_text = mysql_real_escape_string($_POST["thread_text"]);
	$board_id = mysql_real_escape_string($_POST["board_id"]);
	$sticky = "";
	if(!isset($_POST["sticky"])){
		 $sticky = 'false';
	}else{
		$sticky = mysql_real_escape_string($_POST["sticky"]);
	}
	$poster_id = mysql_real_escape_string($_POST["poster_id"]);
	if($thread_name == "" || $thread_text == "" || $board_id == "" || $poster_id == ""){
		echo "Define Fail!";
	}else{
		//echo $poster_id;
		
		$thread = Thread::CreateThread($poster_id,$thread_name, $thread_text, $board_id, $sticky);
	}
	unset($_POST['thread_name']);
	unset($_POST['thread_text']);
	unset($_POST['poster_id']);
	if(isset($_POST["sticky"])) unset($_POST['sticky']);
}
//check board id for sql injection
$board = Board::getBoardFromID($board_id);

$board->loadThreads();
$threads = $board->getThreads();
	$thread_list_size = count($threads);
		echo "<table border='1'><tr><th> Name </th> <th>Last Post </th></tr>";	
	for($i=0;$i<$thread_list_size; $i++){
		$curr_thread = $threads[$i];
		echo "<tr><td><a href='./thread.php?thread_id=".$curr_thread->getID()."'>".$curr_thread->getName()."</a></td>";
		echo "<td> last updated: ".$curr_thread->getTimeUpdated()."</td></tr>";
	}
	echo "</table>";
?>


<form action="" method="post">
<br>Thread Title: <input type="text" name="thread_name" /></br>
<br>First Post: </br><br><textarea name="thread_text" rows="10" cols="40"></textarea></br>
<input type="hidden" name="board_id" value="<?php echo $board_id;?>"/>
<input type="hidden" name="poster_id" value="<?php echo $userid; ?>"/>
<br>Sticky <input type="checkbox" name="sticky"/></br>
<br><input type="submit" name="postThread" value="Post New Thread!" /></br>
</form>

<?php 
include("./includes/footer.php");
?>