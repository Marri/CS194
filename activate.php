<?php
$selected = 'account';
$forNewbies = true;
$cur = 'odd';
include('./includes/header.php');

$key = '';
if(isset($_GET['key']) || isset($_POST['key'])) {
	include('./scripts/activate_account.php');
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="activate.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th class="content-header" colspan="2">Activate your squffies account</th></tr>
<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Activation key </label></th>
<td><input class="width200" autocomplete="off" name="key" type="text" value="<?php echo $key; ?>"></td></tr>
<tr<?php $cur = row($cur); ?>><td></td><td><input class="submit-input" name="activate" type="submit" value="Activate account"></td></tr>
</table>

<?php 
include('./includes/footer.php');
?>