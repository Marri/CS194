<?php
$selected = "squffies";
include("./includes/header.php");

displayErrors($errors);
displayNotices($notices);
?>

<div class='npc'>Kitchen</div>
Some stuff about how you can use the kitchen to make food that is more filling.<br /><br />

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
	if(!$squffy->isTeenager() && !$squffy->isAdult()) { continue; }
	echo '<option value = "' . $squffy->getID() . '">' . $squffy->getName() . '</option>';
}
?>
</select><br />
<input type="submit" name="learn" class="submit-input" value="Start degree at school" /></form>

<?php
}
include('./includes/footer.php');
?>