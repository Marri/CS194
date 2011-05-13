<?php
	include("./includes/header.php");
	
	
	$thread_id = $_GET["thread_id"];
	$board_id = $_GET["board_id"];
	if($thread_id == ""){
		echo "Define Fail!";
	}else{
		Thread::DeleteThread($thread_id);
	}
?>

<a href='http://squffies.com/dev/board.php?board_id=<?php echo $board_id; ?> '> go back to board</a>