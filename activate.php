<?php
$selected = "home";
include("./includes/header.php");
$login = "";
$act_key = "";
$password = "";
if(isset($_GET['act_key'])){
	$act_key =  mysql_real_escape_string($_GET['act_key']);
	$act_error = User::activateUser($act_key);
	if($act_error == ""){
		?>
		<h2>Activation Completed!</h2>
		<?php
	}else{
		?> 
		
		<h2><?php echo $act_error; ?></h2>
		<form class='text-center width100p' action="activate.php" method="get">
			<br><label id="activateLabel">Activation Key: </label><input name="act_key" type="text" value="<?php echo $act_key; ?>"></br>
			<br><input name="activate" type="submit" value="Activate Account"></br>
		</form>
		<?php
	}

}else{
?>

<div class='text-center width100p'><h1>Activate Your Squffy Account!</h1></div>

<form class='text-center width100p' action="activate.php" method="get">
	<br><label id="activateLabel">Activation Key: </label><input name="act_key" type="text"></br>
	<br><input name="activate" type="submit" value="Activate Account"></br>
</form>

<?php
}
include('./includes/footer.php');
?>