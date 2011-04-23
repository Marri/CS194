<?php
include("./includes/header.php");
include("./objects/forums.php");


$id = $_GET['id'];
$forum = new Forum();
$forum->loadSubForums();
?>

<h1>Squffy Forums</h1>

<p>Welcome to the Squffy online forum!</p>


<?php
	$subforums = $forum->getSubForums();
	$subforum_list_size = count($subforums);		
	for($i=0;$i<$subforum_list_size; $i++){
		$curr_subforum = $subforums[$i];
		echo "<table border='1'><tr><th>".$curr_subforum->getName()."</th> <th>Last Post </th></tr>";
		$board_list = $curr_subforum->getBoardList();
		$board_list_size = count($board_list);	
		for($c=0;$c<$board_list_size; $c++){
			$curr_board = $board_list[$c];
			echo "<tr><td><a href='http://squffies.com/dev/board.php?board_id=".$curr_board->getID()."'>".$curr_board->getName()."</a>".$curr_board->getDescription()."</td><td>".$curr_board->getLastPostID()."</td></tr>";
		}
	}
?>

<?php

include('./includes/footer.php');
?>
