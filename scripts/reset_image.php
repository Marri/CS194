<?php
$species = $squffy->getSpecies();
$base = $squffy->getBaseColor();
$eye = $squffy->getEyeColor();
$foot = $squffy->getFootColor();

$markings = array();
$mutations = array();
foreach($squffy->getAppearanceTraits() as $trait) {
	if($trait->getSquare() != 'S' && $trait->getSquare() != 'D') { continue; }
	if($trait->getType() == 1) { $markings[] = array('name'=>$trait->getName(), 'color'=>$trait->getColor()); }
	else { $mutations[] = array('name'=>$trait->getName(), 'color'=>$trait->getColor()); }
}
$markings = array_reverse($markings);
$mutations = array_reverse($mutations);

$display = "image";
$dirUp = true;
include('./scripts/generate_image.php');
imagepng($design, $img, 0);

$width = $height = 125;

$truecolor = imagecreatetruecolor($width, $height);
imagealphablending($truecolor, false);
$color = imagecolortransparent($truecolor, imagecolorallocatealpha($truecolor, 0, 0, 0, 127));
imagefill($truecolor, 0, 0, $color);
imagesavealpha($truecolor, true);

$image_width = imagesx($design);
$image_height = imagesy($design);	
imagecopyresampled($truecolor, $design, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
imagesavealpha($truecolor, true);
$design = $truecolor;
imagepng($design, $thumb, 0);
?>