<?php
if(isset($dirUp)) {
	$img_dir = './images/generate/' . $species . '/' . $species;
} else {
	include_once("../objects/appearance.php");
	$img_dir = '../images/generate/' . $species . '/' . $species;
}

$design = addToBase(NULL, $img_dir, array('name'=>'base', 'color'=>$base), true);
foreach($markings as $trait) {
	$design = addToBase($design, $img_dir, $trait, true);
}
$design = addToBase($design, $img_dir, array('name'=>'feetear', 'color'=>$foot), true);
$design = addToBase($design, $img_dir, array('name'=>'eye', 'color'=>$eye), true);
$design = addToBase($design, $img_dir, array('name'=>'standard'), false);
foreach($mutations as $trait) {
	$name = $trait['name'];
	$trait['name'] = $name . 'c';
	$design = addToBase($design, $img_dir, $trait, true);
	$trait['name'] = $name . 'l';
	$design = addToBase($design, $img_dir, $trait, false);
}

function addToBase($base_image, $img_dir, $trait, $hasColor) {
	$location = $img_dir . 'adult' . $trait['name'] . '.png';
	$img = imagecreatefrompng($location);
	imagesavealpha($img, true);
	$image_width = imagesx($img);
	$image_height = imagesy($img);
	$truecolor = imagecreatetruecolor($image_width, $image_height);
	$truecolor = $img;
	
	if($hasColor) {
		$color = $trait['color'];
		$rgb = Appearance::html2rgb($color);
		imagefilter($truecolor, IMG_FILTER_GRAYSCALE);
		imagefilter($truecolor, IMG_FILTER_BRIGHTNESS, -100);
		imagefilter($truecolor, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]);
	}
	
	if($base_image == NULL) { return $truecolor; }
	
	imagecopy($base_image, $truecolor, 0, 0, 0, 0, $image_width, $image_height);
	imagedestroy($truecolor);
	return $base_image;
};

if(isset($resize) && isset($width) && isset($height)) {
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
}

//TODO save or display?
if(!isset($display)) { $display = "show"; }

if ($display == "image") {
	//Can include the file and use the $design image
} elseif ($display == "notpng") {
	imagepng($design);
	imagedestroy($design);
} else {
	header("Content-type: image/png");
	imagepng($design);
	imagedestroy($design);
}

?>