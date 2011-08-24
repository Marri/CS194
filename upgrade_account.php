<?php
$cost = 50;

if(isset($_POST['upgrade'])) {
	include('./scripts/upgrade_account.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<div class='padding-10 text-left'>Upgrading your Squffies account costs <?php echo $cost; ?> squffy dollars for a six month upgrade.  
Upgraded accounts get various special privileges. Some examples include:
<ul>
	<li>Access to the river, where you can hunt for items once a day.</li>
    <li>Extra farmland, as you can purchase six farm plots instead of four.</li>
    <li>An upgraded-only monthly item every month.</li>
    <li>The ability to use an avatar on the forums.</li>
    <li>And many more!</li>
</ul></div>

<?php
if($user->getLevel() == User::UPGRADE_USER) {
	echo '<b>Your current upgrade expires on</b>: ' . date("F j, Y", strtotime($user->getDateUpgradeEnds())) . '<br />
	<span class="small">Clicking Upgrade Account will add six months to the date shown above</span><br /><br />';
}

if($user->getLevel() == User::ADMIN_USER || $user->getLevel() == User::MOD_USER || $user->getLevel() == User::PERM_UPGRADE_USER) {
?>
<input type="submit" name='upgrade' class="submit-input-disabled" value="upgrade account" disabled="disabled" /><br /><br />
<span class="small-error">Your account is upgraded forever! No need to purchase another upgrade.</span>
<?php 
} elseif($user->getAmount('squffy_dollar') < $cost) {
?>
<input type="submit" name='upgrade' class="submit-input-disabled" value="upgrade account" disabled="disabled" /><br /><br />
<span class="small-error">You cannot afford to upgrade your account.</span>
<?php
} else {
?>
<form action="edit_account.php?view=upgrade" method="post">
<div class="width100p text-center">
<input type="submit" name='upgrade' class="submit-input" value="upgrade account" /></div>
</form>
<?php } ?>

</td></tr></table>

<?php
include('./includes/footer.php');
die();
?>