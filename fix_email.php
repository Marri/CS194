<?php
$selected = 'account';
$forNewbies = true;
$cur = 'odd';
include('./includes/header.php');

$login = '';
$email = '';
if(isset($_POST['fix'])) {
	include('./scripts/update_email.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="fix_email.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th class="content-header" colspan="2">Change your account's email address</th></tr>
<tr<?php $cur = row($cur); ?>><td colspan="2" class="text-center">If you're trying to activate your account but registered with the wrong email, you can change it here.</td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Login</label></th>
<td><input class="width200" autocomplete="off" name="login" type="text" value="<?php echo $login; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Password </label></th>
<td><input class="width200" autocomplete="off" name="pass" type="password"></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">new email address</label></th>
<td><input class="width200" autocomplete="off" name="email" type="text" value="<?php echo $email; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input class="submit-input" name="fix" type="submit" value="Update email address"></td></tr>
</table>
</form>

<?php 
include('./includes/footer.php');
?>