<?php
function print_gzipped_page() {
    global $HTTP_ACCEPT_ENCODING;
    if(headers_sent()) {
        $encoding = false;
    } elseif(strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false) {
        $encoding = 'x-gzip';
    } elseif (strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false) {
        $encoding = 'gzip';
    } else {
        $encoding = false;
    }

    if($encoding){
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit();
    } else {
        ob_end_flush();
        exit();
    }
}

ob_start();
ob_implicit_flush(0);
?>