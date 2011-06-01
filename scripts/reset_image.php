<?php
if($squffy->isEgg()) { die(); }

$species = strtolower($squffy->getSpecies());
$base = $squffy->getBaseColor();
$eye = $squffy->getEyeColor();
$foot = $squffy->getFootColor();
$stage = $squffy->getStage();

$items = array();
$background = '';
if($squffy->getNumItems() > 0) {
	$squffy->fetchItems();
	$i = $squffy->getItems();
	foreach($i as $item) {
		if($item->isClothing()) { $items[] = str_replace(" ", "", $item->getName()); }
		if($item->isBackground()) { $background = str_replace(" ", "", $item->getName()); }
	}
}

$markings = array();
$mutations = array();
foreach($squffy->getAppearanceTraits() as $trait) {
	if($trait->getSquare() != 'S' && $trait->getSquare() != 'D') { continue; }
	if($trait->getType() == 1) { $markings[] = array('name'=>$trait->getName(), 'color'=>$trait->getColor()); }
	else { $mutations[] = array('name'=>$trait->getName(), 'color'=>$trait->getColor()); }
}
$markings = array_reverse($markings);
$mutations = array_reverse($mutations);
$items = array_reverse($items);

if(!isset($dirUp)) { $dirUp = true; }
include_once('./scripts/generate_image.php');
$design = makeImage($species, $markings, $mutations, $base, $eye, $foot, $dirUp, false, 0, 0, $items, $background, $stage);
$img = $squffy->getURL(false);
imagepng($design, $img, 0);

$width = $height = 100;

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
$thumb = $squffy->getThumbnail(false);
imagepng($design, $thumb, 0);
?>