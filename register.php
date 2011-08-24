<?php
$selected = 'account';
$js[] = 'register';
$forNewbies = true;
include('./includes/header.php');

$cur = 'odd';
$user_name = '';
$login_name = '';
$email = '';
$referer = isset($_GET['refer']) ? $_GET['refer'] : NULL;

if(isset($_POST['register'])){
	include('./scripts/process_register.php');
}

if(isset($referer)) {
	if(!Verify::VerifyID($referer)) { $referer = NULL; }
}

displayErrors($errors);
displayNotices($notices);
?>

<form action="register.php" method="post">
<table class="width100p" cellspacing="0"><tr><th class="content-header" colspan="2">Register</th></tr>
		<tr class="odd"><td colspan="2">&nbsp;&nbsp;<img src="./images/icons/exclamation.png" alt="!" /> <b>Had an account already?</b> If you used to play the first version of Squffies, you can migrate your old account once you register.</td></tr>
		<tr class="even"><td colspan="2">&nbsp;</td></tr>

		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="userLabel">Username </label></th><td><input class="width200" autocomplete="off" name="username" type="text" value="<?php echo $user_name; ?>"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="loginLabel">Login Name </label></th><td><input class="width200" autocomplete="off" name="login" type="text" value="<?php echo $login_name; ?>"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="emailLabel">Email Address </label></th><td><input autocomplete="off" class="width200" name="email" type="text" value="<?php echo $email; ?>"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="passwordLabel">Password </label></th><td><input class="width200" autocomplete="off" name="password" type="password"></td></tr>
		<tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="confirmLabel">Confirm Password </label></th><td><input class="width200" name="confirm" type="password"></td></tr>
        <tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"><label id="referLabel">Referred By* </label></th>
		<td id="referCell">		
		<?php
		$shown = false;
		if(isset($referer)) {
			$refer = User::getUserByID($referer);
			if($refer != NULL) {
				$shown = true;
				echo '<b>' . $refer->getUsername() . "</b> (#" . $referer . ") " .
					"<input type='hidden' name='referer' value='" . $referer . "' />" .
					" <a class='small' href='#' id='changeRefer'>Change</a>";
			}
		}
		if(!$shown) {?>
		<input class="width200" id="referred" autocomplete="off" name="referer" type="text" value="<?php echo $referer; ?>"><div id="autoComplete" class="autocomplete width300 hidden"></div>		
		<?php } ?>
		</td></tr>
        <tr<?php $cur = row($cur); ?>><th class="content-miniheader width200"></th><td class="small vertical-top pad-bottom-small"><b>*Optional</b> You may enter either the username or ID of the person who told you about Squffies.</td></tr>
        <tr<?php $cur = row($cur); ?>><td></td><td><input type="checkbox" name="agree" value="true" <?php echo isset($_POST['agree']) ? 'checked' : ''; ?> /> I understand and agree to abide by the <a href="tos.php">Terms of Service</a> and the <a href="privacy.php">Privacy Policy</a>.</td></tr>
		<tr<?php $cur = row($cur); ?>><th colspan="2"><input class="submit-input" name="register" type="submit" value="Register"></td></tr>
</table>      
	</form>

<?php 
/*User::sendActivationKey($user_id, $email);*/
include('./includes/footer.php');		
?>
