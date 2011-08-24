<?php
$selected = 'account';
$forNewbies = true;
$cur = 'odd';
include('./includes/header.php');

$email = '';
if(isset($_POST['resend'])) {
	include('./scripts/resend_email.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="resend.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th class="content-header" colspan="2">Resend your activation key</th></tr>
<tr<?php $cur = row($cur); ?>><td colspan="2" class="text-center">Didn't get our email? No problem! Have your activation key emailed to you again.</td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Email address </label></th>
<td><input class="width200" autocomplete="off" name="email" type="text" value="<?php echo ''; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input class="submit-input" name="resend" type="submit" value="Resend email"></td></tr>
</table>

<?php 
include('./includes/footer.php');
?>