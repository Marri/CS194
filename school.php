<?php
$selected = "squffies";
include("./includes/header.php");

if(isset($_POST['learn'])) {
	$days = 5;
	include('./scripts/squffy_learn.php');
}
displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Public School</b></div>
<div class='npc'>Teacher squffy</div>
Some teacher schpiel about how you can learn a trade here and it will take 5 days and costs 1 pecan.<br /><br />

<?php if($loggedin) { ?>
<form action="school.php" method="post">
Degree: <select size="1" name="degree_id">

<?php
$query = "SELECT * FROM degrees";
$result = runDBQuery($query);
while($d = mysql_fetch_assoc($result)) {
	echo '<option value="' . $d['degree_id'] . '">' . $d['degree_name'] . '</option>';
}
?>
</select><br />
Squffy: <select size="1" name="squffy_id">
<?php
$query = "SELECT * FROM squffies WHERE squffy_owner = $userid";
$squffies = Squffy::getSquffies($query);
foreach($squffies as $squffy) {
	if(!$squffy->isAbleToLearn()) { continue; }
	echo '<option value = "' . $squffy->getID() . '">' . $squffy->getName() . '</option>';
}
?>
</select><br />
<input type="submit" name="learn" class="submit-input" value="Start degree at school" /></form>

<?php
}
include('./includes/footer.php');
?>