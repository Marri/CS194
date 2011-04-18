<?php
include("./includes/header.php");
//include("./includes/connect.php");
include("./objects/forums.php");


$id = $_GET['id'];
$forum = new Forum();
$forum->loadSubForums();
?>
<html>
<body>

<h1>Squffy Forums</h1>

<p>Welcome to the Squffy online forum!</p>


<?php
	$forum->displaySubForums();
?>

</body>
</html>
<?php

include('./includes/footer.php');
?>
