<?php
$selected = 'account';
include("./includes/header.php");

if(!isset($_GET['id'])) {
	if(!$loggedin) {
		displayErrors(array('You must pick a profile to view.'));
		include('./includes/footer.php');
		die();
	}
	$id = $userid;
} else {
	$id = $_GET['id'];
	if(!Verify::VerifyID($id)) {
		displayErrors(array('You must pick a profile to view.'));
		include('./includes/footer.php');
		die();
	}
}
$profile = User::getUserByID($id);
if($profile == NULL) {
	displayErrors(array('That profile does not exist.'));
	include('./includes/footer.php');
	die();
}

$title = possessive($profile->getUsername()) . ' profile';
$links = array(
	array('name'=>'profile', 'url'=>"profile.php?id=" . $id),
	array('name'=>'badges', 'url'=>"profile.php?id=" . $id . '&view=badges'),
	array('name'=>'drey', 'url'=>"drey.php?id=" . $id),
	array('name'=>'nursery', 'url'=>"nursery.php?id=" . $id),
);

drawMenuTop($title, $links);

$view = '';
if(isset($_GET['view'])) { $view = $_GET['view']; }

if($view == 'badges') {
	include('./profile_badges.php');
}
?>
<table class="width100p text-left">
<tr><th class="content-miniheader width200">User</th><td><?php echo $profile->getUsername(); ?></td></tr>
<tr><th class="content-miniheader width200">User level</th><td><?php echo $profile->getLevelName(); ?></td></tr>
<tr><th class="content-miniheader width200">Member since</th><td>
<?php
$query = "SELECT date_joined FROM log_register WHERE user_id = $id";
$result = runDBQuery($query);
$info = @mysql_fetch_assoc($result);
echo date("F j, Y", strtotime($info['date_joined']));
?>
</td></tr>
<tr><th class="content-miniheader width200">Last seen</th><td>
<?php
$seen = $profile->getLastSeen();
echo date("g:i a \o\\n F j, Y", strtotime($seen));
?>
</td></tr>
<?php
$secsSince = time() - strtotime($seen);
$minSince = $secsSince / 60;
if($minSince < 15) {
	echo '<tr><th colspan="2" class="success text-center">Online now!</th></tr>';
}
?>
</table>
</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>