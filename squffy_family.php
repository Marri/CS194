<table class="width100p" cellspacing="0">

<?php 
if(!$squffy->isCustom()) {
	echo '<tr><th colspan="4" class="content-subheader">family tree</th></tr>';
	$family = $squffy->getFamily();
	$mom = Squffy::getSquffyByID($family['mother']);
	$dad = Squffy::getSquffyByID($family['father']);
	
	if(!$mom->isCustom()) { 
		$mom->fetchFamily();
		$m = Squffy::getSquffyByID($mom->getMotherID());
		$d = Squffy::getSquffyByID($mom->getFatherID());
		echo '<tr><td class="female bordered width150"><img src="' . $m->getThumbnail() . '" /><br />'.$m->getLink().'</td>
		<td class="male bordered width150"><img src="' . $d->getThumbnail() . '" /><br />'.$d->getLink().'</td>';
	} elseif(!$dad->isCustom()) {
		echo '<tr><td class="width150"></td><td class="width150"></td>';
	}
	if(!$dad->isCustom()) { 
		$dad->fetchFamily();
		$m = Squffy::getSquffyByID($dad->getMotherID());
		$d = Squffy::getSquffyByID($dad->getFatherID());
		echo '<td class="female bordered width150"><img src="' . $m->getThumbnail() . '" /><br />'.$m->getLink().'</td>
		<td class="male bordered width150"><img src="' . $d->getThumbnail() . '" /><br />'.$d->getLink().'</td></tr>';
	} elseif(!$mom->isCustom()) {
		echo '<td class="width150"></td><td class="width150"></td></tr>';
	}
	
	if(!$mom->isCustom()) { 
		echo '<tr><td class="width150"><img src="./images/icons/arrow_down.png" /></td><td class="width150"><img src="./images/icons/arrow_down.png" /></td>';
	} elseif(!$dad->isCustom()) {
		echo '<tr><td class="width150"></td><td class="width150"></td>';
	}
	
	if(!$dad->isCustom()) { 
		echo '<td class="width150"><img src="./images/icons/arrow_down.png" /></td><td class="width150"><img src="./images/icons/arrow_down.png" /></td></tr>';
	} elseif(!$mom->isCustom()) {
		echo '<td class="width150"></td><td class="width150"></td></tr>';
	}
	
	echo '<tr><td colspan="2" class="text-center width300 bordered female">
	<img src="' . $mom->getThumbnail() . '" /><br />'.$mom->getLink().'</td>
	<td colspan="2" class="text-center width300 bordered male">
	<img src="' . $dad->getThumbnail() . '" /><br />'.$dad->getLink().'</td></tr>
	<tr><td colspan="2" class="width300"><img src="./images/icons/arrow_down.png" /></td><td colspan="2" class="width300"><img src="./images/icons/arrow_down.png" /></td></tr>
	<tr><td class="width150"></td><td colspan="2" class="width300 bordered ';
	if($squffy->getGender() == 'F') { echo 'fe'; }
	echo 'male"><img src="' . $squffy->getURL() . '" /></td><td class="width150"></td></tr>';
} else {
	echo '<tr><td class="width150"></td><td colspan="2" class="width300"><img src="' . $squffy->getURL() . '" /></td><td class="width150"></td></tr>';
}
$mom_dad = $squffy->getGender() == 'F' ? 'mother' : 'father';
$query = 'SELECT * FROM squffy_family WHERE ' . $mom_dad . '_id = ' . $squffy->getID();
$result = runDBQuery($query);
if(@mysql_num_rows($result) > 0) {
	?>
	<tr><th colspan="4" class="content-subheader">Children</th></tr></table><table class="width100p">
	<?php 
	$i = 0;
	while($info = @mysql_fetch_assoc($result)) {
		$child = Squffy::getSquffyByID($info['squffy_id']);
		if($i % 4 == 0) { echo '<tr>'; }
		echo '<td class="width150 bordered ';
		if($child->getGender() == 'F') { echo 'fe'; }
		echo 'male"><img src="' . $child->getThumbnail() . '" /><br>' . $child->getLink() . '</td>';
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