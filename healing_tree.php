<?php
$selected = 'world';
include("./includes/header.php");

if(isset($_POST['heal']) && $loggedin) {
	include('./scripts/squffy_heal.php');
}

displayErrors($errors);
displayNotices($notices);
?>
<div class="content-header width100p"><b>Healing Tree</b></div>
<div class='div-center width450'><img class='npc no-border width450' src='./images/npcs/healer.jpg' /></div>
&nbsp;Some doctor schpiel about how you can heal your squffy here; it will cost 1 pistachio.<br /><br />
<?php if($loggedin) { ?>
    <form action="healing_tree.php" method="post">
    <?php
    $query = "SELECT * FROM squffies WHERE squffy_owner = $userid AND health < 100";
    $squffies = Squffy::getSquffies($query);
	$inventory = $user->getInventory();
    if(sizeof($squffies) == 0) {
		echo '<span class="small-error">You have no sick squffies for me to heal!</span>';
	} elseif ($inventory['pistachio'] < 1) {
		echo '<span class="small-error">You cannot afford a healing!</span>';
	} else {	
        echo '&nbsp;Squffy: <select size="1" name="squffy_id">';
        foreach($squffies as $squffy) {
            if(!$squffy->isTeenager() && !$squffy->isAdult()) { continue; }
            echo '<option value = "' . $squffy->getID() . '">' . $squffy->getName() . ' (' . $squffy->getHealth() . '% health)</option>';
        }
		echo '</select><br /><input type="submit" class="submit-input margin-top-small" name="heal" value="Heal squffy" /></form>';
	}
}
include('./includes/footer.php');
?>