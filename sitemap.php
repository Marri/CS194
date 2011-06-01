<?php
$selected = "help";
include("./includes/header.php");
?>
<div class='margin-left-small'>
<b>World Locations</b><br />
<li><a href="market.php">Market</a><br />
<li><a href="trading.php">Trading Circle</a><br />
<li><a href="healing_tree.php">Healing tree</a><br />
<li><a href="school.php">School</a><br />
<li><a href="kitchen.php">Kitchen</a><br />
<li><a href="woodshop.php">Woodshop</a><br />
<li><a href="smithy.php">Smithy</a><br />
<li><a href="seymose.php">Seymose the Trader</a><br />
<li><a href="olivia.php">Olivia the Merchant</a><br /><br />

<b>User Belongings</b><br />
<li><a href="drey.php">Drey</a><br />
<li><a href="view_squffy.php?id=112">View squffy</a><br />
<li><a href="edit_squffy.php?id=112">Edit squffy</a><br />
<li><a href="hoard.php">Inventory ("Item hoard")</a><br />
<li><a href="pantry.php">Pantry</a><br /><br />

<b>Non-Game Interactions</b><br />
<li><a href="forums.php">Forums</a><br />
<li><a href="messages.php">Messages</a><br />
<li><a href="notifications.php">Notifications</a><br />
<li><a href="thanks.php">SD purchase landing page</a><br /><br />

<b>Create customs</b><br />
<li><a href="design.php">Design custom squffy</a><br />
<li><a href="designs.php">Your squffy designs</a><br />
<li><a href="custom.php">Create custom squffy</a><br /><br />

<b>Crons</b><br />
<li><a href="crons/finish_pregnancy.php">Cron: finish pregnancy</a><br />
<li><a href="crons/finish_school.php">Cron: finish school</a><br />
<li><a href="crons/reset_rights.php">Cron: reset rights</a><br />
<li><a href="crons/finish_cooking.php">Cron: finish cooking</a><br />
</div>

<?php
include('./includes/footer.php');
?>
