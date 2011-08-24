<?php
$selected = 'account';
$forLoggedIn = true;
$cur = 'odd';
include('./includes/header.php');

$name = '';
$email = '';
$note = '';
if(isset($_POST['invite'])) {
	include('./scripts/invite_friend.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="refer.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th colspan="2" class="content-header">Refer a friend</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">Name</td><td><input name="friend" type="text" value="<?php echo $name; ?>" /></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200">Email</td><td><input name="email" type="text" value="<?php echo $email; ?>" /></td></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200 vertical-top">Optional Note</td><td><textarea name="note" class="width400"><?php echo $note; ?></textarea></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input type="submit" class="submit-input" name="invite" value="Send invitation" /></td></tr>
</table>
</form>

<?php 
include('./includes/footer.php');
?>