<?php
$selected = "interact";
$cur = 'odd';
include("./includes/header.php");
?>

<div class="content-header width100p"><b>Online now</b></div>
<?php
$query = "SELECT * FROM `users` WHERE (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`date_last_seen`))/60 <= 15 ORDER BY level_id, date_last_seen";
$result = runDBQuery($query);
if(@mysql_num_rows($result) < 1) { 
	echo '<div class="text-center italic"><br />There are no users currently online.</div>';
	include('./includes/footer.php');
	die();
}

echo '<table class="width100p" cellspacing="0">';
while($info = @mysql_fetch_assoc($result)) {
	echo '<tr';
	$cur = row($cur);
	echo '>
	<td class="width50p text-center"><a href="profile.php?id=' . $info['user_id'] . '">' . $info['username'] . '</a>';
	if($info['level_id'] == User::ADMIN_USER) { echo ' <img src="./images/icons/admin.png" alt="A" title="Administrator" />'; }
	if($info['level_id'] == User::MOD_USER) { echo ' <img src="./images/icons/mod.png" alt="M" title="Moderator" />'; }
	echo '</td>
	<td class="width50p text-center">Last seen at ' . date("g:i a n/j/Y", strtotime($info['date_last_seen'])) . '</td>
	</tr>';
}
?>
<tr><td colspan="2" class="text-center">
<img src="./images/icons/admin.png" alt="A" /> Administrator &nbsp;&nbsp;&nbsp;
<img src="./images/icons/mod.png" alt="M" /> Moderator
</td></tr>
</table>

<?php
include('./includes/footer.php');
?>