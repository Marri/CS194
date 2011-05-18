<table class="width100p">
<tr>
<th class="width200"><?php echo history(strtotime($squffy->getBirthday())); ?></th>
<td class="text-left"><?php echo $squffy->getName(); ?> was born.</td>
</tr>

<?php
$history = $squffy->getHistory();
foreach($history as $event) {
?>
<tr>
<th class="width200"><?php echo history($event['time']); ?></th>
<td class="text-left"><?php echo $event['text']; ?></td>
</tr>
<?php } ?>
</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');

function history($date) {
	return date("M d, Y h:m A", $date);
}
?>