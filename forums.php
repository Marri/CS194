<?php
include("./includes/header.php");
include("./objects/forums.php");


//$id = $_GET['id'];
$forum = new Forum();
$forum->loadSubForums();
?>

<h1>Squffy Online Community</h1>

<h2>Welcome to the Squffy online forums!</h2>


<?php
	$subforums = $forum->getSubForums();
	$subforum_list_size = count($subforums);		
	for($i=0;$i<$subforum_list_size; $i++){
		$curr_subforum = $subforums[$i];
		echo "<table border='1'><tr><th>".$curr_subforum->getName()."</th><th>Description</th> <th>Last Poster </th></tr>";
		$board_list = $curr_subforum->getBoardList();
		$board_list_size = count($board_list);	
		for($c=0;$c<$board_list_size; $c++){
			$curr_board = $board_list[$c];
			$poster = User::getUserByID($curr_board->getLastPostID());
			$poster_name = "";
			if($poster != NULL) $poster_name = $poster->getUsername(); 
			echo "<tr><td><a href='./board.php?board_id=".$curr_board->getID()."'>".$curr_board->getName()."</a></td> <td>".$curr_board->getDescription()."</td><td>".$poster_name."</td></tr>";
		}
		echo "</table>";
	}
?>

<?php

include('./includes/footer.php');
?>
