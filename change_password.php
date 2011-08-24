<?php
if(isset($_POST['change'])) {
	include('./scripts/change_password.php');
}
displayErrors($errors);
displayNotices($notices);
?>

<div class='padding-10 text-left'></div>

<form action="edit_account.php?view=password" method="post">
<table cellspacing="0" class="width100p text-left">
<tr><th colspan="2" class="content-subheader">change your password</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">Old password</th>
<td><input type="password" name="old_pass" autocomplete="off" /></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">new password</th>
<td><input type="password" name="new_pass" autocomplete="off" /></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">confirm new password</th>
<td><input type="password" name="conf" autocomplete="off" /></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input type="submit" name='change' class="submit-input" value="change password" /></th></tr>
</table>
</form>

</td></tr></table>

<?php
include('./includes/footer.php');
die();
?>