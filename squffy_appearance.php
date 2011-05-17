<?php
$t = $squffy->getAppearanceTraits();
$visible = array();
$carried = array();
foreach($t as $trait) {
	if($trait->getSquare() != 'C') { $visible[] = $trait; }
	else { $carried[] = $trait; }
}
?>
<table class="width100p">
<tr><th colspan="3" class="content-subheader">standard colors</th></tr>
<tr><td class="content-subheader width150">Base</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getBaseColor(); ?>"></div></td>
<td class="text-left"><?php echo $squffy->getBaseColor(); ?></td></tr>
<tr><td class="content-subheader width150">Eye</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getEyeColor(); ?>"></div></td>
<td class="text-left"><?php echo $squffy->getEyeColor(); ?></td></tr>
<tr><td class="content-subheader width150">Feet & Ears</td>
<td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $squffy->getFootColor(); ?>"></div></td>
<td class="text-left"><?php echo $squffy->getFootColor(); ?></td></tr>
<?php if(sizeof($visible) > 0) { ?>
<tr><th colspan="3" class="content-subheader">visible traits</th></tr>
<?php foreach($visible as $trait) { ?>
<tr>
    <td class="content-subheader width150"><?php echo $trait->getTitle(); ?></td> 
    <td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $trait->getColor(); ?>"></div></td>
    <td class="text-left"><?php echo $trait->getColor(); ?></td>
</tr>
<?php } } ?>
<?php if(sizeof($carried) > 0) { ?>
<tr><th colspan="3" class="content-subheader">carried traits</th></tr>
<?php foreach($carried as $trait) { ?>
<tr>
    <td class="content-subheader width150"><?php echo $trait->getTitle(); ?></td>        
    <td class="width80 text-center"><div class="color-box" style="background-color: #<?php echo $trait->getColor(); ?>"></div></td>
    <td class="text-left"><?php echo $trait->getColor(); ?></td>
</tr>
<?php } } ?>
</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>