<?php
$selected = 'account';
$forNewbies = true;
$cur = 'odd';
include('./includes/header.php');

$login = '';
$confirm = '';
if(isset($_POST['reset'])) {
	include('./scripts/reset_password.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="reset.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th class="content-header" colspan="2">Reset Your password</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Login</label></th>
<td><input class="width200" autocomplete="off" name="login" type="text" value="<?php echo $login; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Confirm Login </label></th>
<td><input class="width200" autocomplete="off" name="confirm" type="text" value="<?php echo $confirm; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input class="submit-input" name="reset" type="submit" value="Email a temporary password"></td></tr>
</table>
</form>

<?php
include('./includes/footer.php');
?>