<?php
$selected = 'interact';
include("./includes/header.php");

//$notifications = $user->getNotifications();
$notifications = Notification::getNotificationsByUser($userid);
$cur = 'odd';
?>

<div class='text-center width100p content-header'><b>Notifications</b></div>

<table cellspacing="0" class="width100p">
	<?php
	foreach($notifications as $curr_note) {
		?>
		<tr<?php $cur = row($cur); ?>>
        	<td class="width50 text-center">
            	<?php if($curr_note->unread()) { ?><img src="./images/icons/star.png" alt="*" /> <?php } ?>
            </td>
        	<td class="width300"><?php echo $curr_note->getType() ?></td>
            <td class="width300">Date will go here</td>
            <td><a href="#">Respond</a></td>
        </tr>
		<?php
	}
?>
</table>

<?php
function row($cur) {
	echo ' class="' . $cur . '"';
	return $cur == "odd" ? "even" : "odd";
}
include('./includes/footer.php');
?>