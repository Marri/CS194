<?php
include("./includes/header.php");
include("./objects/forums.php");
$board_id = $_GET["board_id"];
//check board id for sql injection
$board = Board::getBoardFromID($board_id);
echo $board;
$board->loadThreads();
$threads = $board->getThreads();
	$thread_list_size = count($threads);
	echo " there are ".$thread_list_size." threads";
	//echo "<table border='1'><tr><th> Name </th> <th>Last Post </th></tr>";	
	for($i=0;$i<$thread_list_size; $i++){
		$curr_thread = $threads[$i];
		echo "<br><a href='http://squffies.com/dev/thread.php?thread_id=".$curr_thread->getID()."'> ".$curr_thread->getName()."</a>";
		echo " last updated: ".$curr_thread->getTimeUpdated()."</br>";
	}
?>


<form action="createThread.php" method="get">
<br>Thread Title: <input type="text" name="thread_name" /></br>
<br>First Post: </br><br><textarea name="thread_text" rows="10" cols="40"></textarea></br>
<input type="hidden" name="board_id" value="<?php echo $board_id;?>"/>
<input type="hidden" name="poster_id" value="45"/>
<br>Sticky <input type="checkbox" name="sticky"/></br>
<br><input type="submit" /></br>
</form>
<form action="deleteThread.php" method="get">
<br>Thread ID: <input type="text" name="thread_id" /></br>
<input type="hidden" name="board_id" value="<?php echo $board_id;?>"/>
<br><input type="submit" /></br>
</form>

<?php 
include("./includes/footer.php");
?>