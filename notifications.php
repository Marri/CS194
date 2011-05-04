<?php
$selected = "home";
include("./includes/header.php");
include("./objects/notification.php");

$user = NULL;
$notifications = NULL;
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}
if($user != NULL){
	$notifications = $user->getNotifications();
}
?>

<div class='text-center width100p'><h1>Notifications</h1></div>

<table>
	<tr><th>Notification Type</th><th>unread</th></tr>
	<?php
	echo count($notifications);
	for($i = 0; $i < count($notifications); $i++){
	
		$curr_note = $notifications[$i];
		?>
		<tr><td><?php echo $curr_note->getNoteType() ?></td><td><?php echo $curr_note->unread(); ?></td></tr>
		<?php
	}
?>
</table>

<?php
include('./includes/footer.php');
?>