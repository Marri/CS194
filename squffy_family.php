<?php
?>
<table class="width100p">
<?php if(!$squffy->isCustom()) { ?>
<tr><th colspan="3" class="content-subheader">family tree</th></tr>
<?php } 
$mom_dad = $squffy->getGender() == 'F' ? 'mother' : 'father';
$query = 'SELECT * FROM squffy_family WHERE ' . $mom_dad . '_id = ' . $squffy->getID();
$result = runDBQuery($query);
if(@mysql_num_rows($result) > 0) {
	?>
	<tr><th colspan="4" class="content-subheader">children</th></tr>
	<?php 
	$i = 0;
	while($info = @mysql_fetch_assoc($result)) {
		$child = Squffy::getSquffyByID($info['squffy_id']);
		if($i % 4 == 0) { echo '<tr>'; }
		echo '<td><img src="' . $child->getThumbnail() . '" /><br>' . $child->getLink() . '</td>';
		if($i % 4 == 3) { echo '</tr>'; }
		$i++;
	}
	while($i % 4 != 0) { echo '<td></td>'; $i++; }
	echo '</tr>';
} ?>
</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>