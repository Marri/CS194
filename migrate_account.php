<?php
if(isset($_POST['migrate'])) {
	include('./scripts/migrate_account.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<div class='padding-10 text-left'>If you were a member for the four years of Squffies' first appearance, you can migrate your old account
to your new one here.</div>

<form action="edit_account.php?view=migrate" method="post">
<table cellspacing="0" class="width100p text-left">
<tr><th colspan="2" class="content-subheader">sign in to your old account</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">Old login</th>
<td><input type="text" name="old_login" autocomplete="off" /></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">Old password</th>
<td><input type="password" name="old_pass" autocomplete="off" /></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input type="submit" name='migrate' class="submit-input" value="Migrate account" /></th></tr>
</table>
</form>

</td></tr></table>

<?php
include('./includes/footer.php');
die();
?>