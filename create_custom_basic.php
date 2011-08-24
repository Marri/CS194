<form action="create_custom.php" method="post">
<table class="width100p" cellspacing="0">
<tr><th colspan="4" class="content-header">Pick a Design</th></tr>
<?php
$add = '';
if($num %4 == 3) { $add = '<td></td>'; }
elseif($num %4 == 2) { $add = '<td></td><td></td>'; }
elseif($num %4 == 1) { $add = '<td></td><td></td><td></td>'; }

$i = 0;
foreach($designs as $design) {
	$numTraits = $design->getNumTraits();
	
	if($i % 4 == 0) { echo '<tr>'; }
	echo '<td class="text-center vertical-top width200"><b>' . $design->getName() . '</b><br />
	<img src="' . $design->getThumbnail() . '" /><br />';
	if($numTraits <= $item_info['num'] && $design->getSpecies() == $item_info['species']) { echo '<input type="radio" name="design" value="' . $design->getID() . '"> Use design'; }
	if($design->getSpecies() != $item_info['species']) { echo '<span class="small-error">Wrong species!</span><br />'; }
	if($numTraits> $item_info['num']) { echo '<span class="small-error">Too many traits!</span>'; }
	echo '</td>';
	$i++;
	if($i % 4 == 0) { echo '</tr>'; }
}
if($num %4 > 0) { echo $add . '</tr>'; }

echo '<tr><td colspan="4">
Name: <input type="text" class="margin-bottom-small" name="squffy_name" /><br />
Gender: <input type="radio" checked class="margin-bottom-small" name="gender" value="M" /> Male <input type="radio" name="gender" value="F" /> Female<br />
<input type="hidden" name="pay_type" value="' . $pay_type . '" />
<input type="submit" class="submit-input margin-top-small" value="Create custom" name="create" /></td></tr>';
?>
</table>
</form>