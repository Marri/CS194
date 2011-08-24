<?php
$selected = 'squffies';
include('./includes/header.php');
?>
<div class="content-header width100p"><b>Design and Create Custom Squffies</b></div>

<div style="padding: 10px;">
&nbsp;&nbsp;&nbsp;&nbsp;Here, you can create a brand new squffy from scratch! To create a custom squffy, first create and save a design in the <a href="design.php">designer</a>, update them in the <a href="designs.php">editor</a> if necessary, then create a squffy matching that design in the <a href="create_custom.php">creator</a>.<br /><br />
&nbsp;&nbsp;&nbsp;&nbsp;Each user receives one free tree squffy and one free ground squffy.  These starter squffies stay with you forever, and cannot be sold or transferred.  After that, if you wish to create more custom squffies, you will need one of the special items for sale from <a href="olivia.php">Olivia the Merchant</a>.<br /><br />
What would you like to do?<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="design.php"><img src="./images/icons/palette.png" alt="o" />&nbsp;&nbsp;Design a custom squffy</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="designs.php"><img src="./images/icons/pencil.png" alt="/" />&nbsp;&nbsp;Manage your saved designs</a><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="create_custom.php"><img src="./images/icons/add.png" alt="+" />&nbsp;&nbsp;Create a custom squffy from one of your saved designs</a><br />
</div>

<?php
include('./includes/footer.php');
?>