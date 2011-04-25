<?php
$filename = $_GET['file'];
$filetype = $_GET['type'];

//GZips and includes the given filename
if($filename && ctype_alpha(str_replace("_","",$filename)) && $filetype && ctype_alpha($filetype)) {
	$folder = $filetype;
	if(isset($_GET['folder'])) { $folder = $_GET['folder']; }
	$path = '../' . $folder . '/' . $filename . '.' . $filetype;
	if(file_exists($path)) { 
		include('./gzip.php');
		include($path);
		print_gzipped_page();
	}
}
?>