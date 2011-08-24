<?php
$selected = 'account';
$forLoggedIn = true;
$cur = 'odd';
include('./includes/header.php');

$query = "SELECT * FROM referral_reward_claims WHERE user_id = $userid";
$result = runDBQuery($query);
$claims = @mysql_num_rows($result);
if($claims > 0) {
	$info = @mysql_fetch_assoc($result);
	$claims = $info['num_claimed'];
}

$query = "SELECT * FROM log_accepted_referrals WHERE referer_id = $userid";
$log_result = runDBQuery($query);
$num = @mysql_num_rows($log_result);
$points = $num - $claims;

if(isset($_POST['redeem'])) {
	include('./scripts/redeem_referral.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<table class="width100p" cellspacing="0">
<tr><th class="content-header">Referrals sent</th><th class="content-header">Referrals accepted</th></tr>
<tr><td class="vertical-top text-center">
<?php
$query = "SELECT * FROM log_referrals WHERE refer_id = $userid";
$result = runDBQuery($query);
if(@mysql_num_rows($result) > 0) {
	while($info = @mysql_fetch_assoc($result)) {
		echo $info['email'] . '<br />';
	}
} else {
	echo '<span class="italic">You have not sent an invitation to anyone yet!</span>';
}
?>
</td>
<td class="vertical-top text-center">
<?php
if($num < 1) {
	echo '<span class="italic">No one has accepted a referral from you yet!</span>';
} else {
	while($info = @mysql_fetch_assoc($log_result)) {
		echo '<a href="profile.php?id=' . $info['referred_id'] . '">' . $info['referred_username'] . '</a><br />';
	}
}
?>
</td>
</tr>
</table>
<br /><br />

<table class="width100p" cellspacing="0">
<tr><th colspan="4" class="content-header">Referral prizes</th></tr>
<tr><td colspan="4">&nbsp;&nbsp;&nbsp;<b>People referred</b>: <?php echo $num; ?></td></tr>
<tr><td colspan="4">&nbsp;&nbsp;&nbsp;<b>Unused points</b>: <?php echo $points; ?></td></tr>
<tr><td colspan="4"><br /></td></tr>
<?php
$query = "SELECT * FROM referral_rewards";
$result = runDBQuery($query);
while($reward = @mysql_fetch_assoc($result)) {
	$item = Item::getItemByID($reward['item_id']);
	$col = $item->getColumnName();
	$key = str_replace("_", "", $col);
	$img = "../../images/items/$key.png";
	
	echo '<tr class="item">
	<td class="text-center width100 vertical-top"><img class="item " src="' . $img . '" alt="' . $item->getName() . '" /></td>
    <td class="text-center vertical-top width200"><b>' . $reward['quantity'] . ' ';
	if($reward['quantity'] > 1) { echo pluralize($item->getName()); }
	else { echo $item->getName(); }
	echo '</b></td>
    <td class="text-center width200 vertical-top">Points required: ' . $reward['num_referred'] . '</td>
    <td class="text-left vertical-top">';
	if($reward['num_referred'] <= $points) { 
		echo '<form action="referrals.php" method="post">
		<input type="hidden" name="prize" value="' . $reward['prize_id'] . '" />
		<input type="submit" name="redeem" value="Claim" class="submit-input" />';
	} else {
		echo '<input type="submit" value="Too expensive" class="submit-input-disabled" disabled="disabled"; />';
	}
	echo '</form></td></tr>';
}
?>
</table>

<?php 
include('./includes/footer.php');
?>