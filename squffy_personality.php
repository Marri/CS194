<table class="width100p" cellspacing="0">
<?php
$traits = $squffy->getPersonalityTraits();
?>
<tr><th colspan="2" class="width50p content-subheader">Strength</th><th colspan="2" class="width50p content-subheader">Strength</th></tr>
<tr><th class="vertical-top width100"><?php echo $traits['strength1_name']; ?></th><td class="vertical-top text-left small width200"><?php echo $traits['strength1_desc']; ?></td>
<th class="vertical-top width100"><?php echo $traits['strength2_name']; ?></th><td class="vertical-top text-left small width200"><?php echo $traits['strength2_desc']; ?></td></tr>
<tr><th colspan="2" class="width50p content-subheader">Weakness</th><th colspan="2" class="width50p content-subheader">Weakness</th></tr>
<tr><th class="vertical-top width100"><?php echo $traits['weakness1_name']; ?></th><td class="vertical-top text-left small width200"><?php echo $traits['weakness1_desc']; ?></td>
<th class="vertical-top width100"><?php echo $traits['weakness2_name']; ?></th><td class="vertical-top text-left small width200"><?php echo $traits['weakness2_desc']; ?></td></tr>
</table>

</td>
</tr>
</table>

<?php
include('./includes/footer.php');
?>