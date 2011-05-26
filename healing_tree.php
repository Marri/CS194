<?php
include("./includes/header.php");

if(isset($_POST['learn'])) {
	$days = 5;
	include('./scripts/squffy_learn.php');
}
displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Healing Tree</b></div>
<div class='npc'>Doctor squffy</div>
Some doctor schpiel about how you can heal your squffy here; it will take 1 day and cost 2 pecans.<br /><br />

<?php if($loggedin) { ?>
    <form action="healing_tree.php" method="post">
    <?php
    $query = "SELECT * FROM squffies WHERE squffy_owner = $userid AND health < " . Squffy::SICK;
    $squffies = Squffy::getSquffies($query);
	$inventory = $user->getInventory();
    if(sizeof($squffies) == 0) {
		echo '<span class="small-error">You have no sick squffies for me to heal!</span>';
	} elseif ($inventory['pecan'] < 2) {
		echo '<span class="small-error">You cannot afford a healing!</span>';
	} else {	
        echo 'Squffy: <select size="1" name="squffy_id">';
        foreach($squffies as $squffy) {
            if(!$squffy->isTeenager() && !$squffy->isAdult()) { continue; }
            echo '<option value = "' . $squffy->getID() . '">' . $squffy->getName() . '</option>';
        }
		echo '</select><br /><input type="submit" class="submit-input margin-top-small" name="heal" value="Heal squffy" /></form>';
	}
}
include('./includes/footer.php');
?>