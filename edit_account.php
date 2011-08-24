<?php
$forLoggedIn = true;
$selected = 'account';
$cur = 'odd';
include("./includes/header.php");

$links = array(
	array('name'=>'edit profile', 'url'=>"edit_account.php"),
	array('name'=>'change password', 'url'=>"edit_account.php?view=password"),
	array('name'=>'upgrade account', 'url'=>"edit_account.php?view=upgrade"),
	array('name'=>'migrate account', 'url'=>"edit_account.php?view=migrate"),
	array('name'=>'go on vacation', 'url'=>"edit_account.php?view=vacation"),
);

drawMenuTop('edit your account', $links);

$view = '';
if(isset($_GET['view'])) { $view = $_GET['view']; }

if($view == 'migrate') {
	include('./migrate_account.php');
}

if($view == 'password') {
	include('./change_password.php');
}

if($view == 'upgrade') {
	include('./upgrade_account.php');
}

if($view == 'vacation') {
	include('./start_vacation.php');
}
?>
</td></tr></table>


<?php /*
if(!isset($_POST['migrate'])){ ?>
	<div class='text-center width100p'><h1>Profile</h1></div>
	<h1> Migrate Old Account </h1>
	<form action="" method="post">
		<br><label id="loginLabel">Login Name: </label><input name="login" type="text"></br>
		<br><label id="passwordLabel">Password: </label><input name="password" type="password"></br>
		<br><input name="migrate" type="submit" value="Migrate Old Account" class="margin-top-small submit-input"></br>
	</form>
<?php }else{
		$migrated = $user->migrateAccount($_POST['login'], $_POST['password']);
		if($migrated){
		?>
			<h1> Old Account  Migrated! </h1>
<?php }else{
			?><h1> Migration Failed </h1> <?php
		}
	}
	*/
include('./includes/footer.php');
?>