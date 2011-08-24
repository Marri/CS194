<?php
if(isset($_POST['vacation'])) {
	include('./scripts/start_vacation.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<div class='padding-10 text-left'>If you are going on vacation and don't want your squffies to get hungry or sick while you're gone, 
you can set your account to Vacation mode.  You must enable it for a minimum of three days.  It has the following effects:
<ul>
<li>You will be logged out. If there's anything you want to do before leaving, <b>do that first!</b>
<li>You will not be able to log into Squffies.
<li>Your squffies will not get hungrier or sicker.
<li>Your pregnant squffies will lay their eggs later.
<li>Your farms will not get drier, sprout more weeds, or die.
<li>If applicable, your account upgrade will be extended.
</div>

<form action="edit_account.php?view=vacation" method="post">
<table cellspacing="0" class="width100p text-left">
<tr><th colspan="2" class="content-subheader">start your vacation</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">how long?</th>
<td><input type="text" class="width50" name="num_days" autocomplete="off" /> days</td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input type="submit" name='vacation' class="submit-input" value="go on vacation" /></th></tr>
</table>
</form>

</td></tr></table>

<?php
include('./includes/footer.php');
die();
?>