<?php
require_once(__DIR__ . '/inc/header.inc.php');
define('__ROOT__', dirname(__FILE__));
require_once(__DIR__.'/inc/api.secret.inc.php');
require_once(__DIR__.'/inc/api.inc.php');

$url_array = explode('/', $_SERVER['REQUEST_URI']);
if (count($url_array) < 2) {
    $error_404 = true;
}
$url_array = array_reverse($url_array);
$PARTID = $url_array[0];
$PARTID_MD5 = $url_array[1];
$USER_TYPE = $url_array[2];

//check validity of URL
if (ctype_xdigit($PARTID_MD5) && strlen($PARTID_MD5) == 32 && strlen($PARTID) == 6 && $PARTID_MD5 == (md5($PARTID))) {
    $PARTID_MD5_first5 = substr($PARTID_MD5, 0, 5);
    $matNr_postfix = substr(base_convert($PARTID_MD5_first5, 16, 10), 0, 3);
    $matNr = $PARTID.substr(base_convert($PARTID_MD5_first5, 16, 10), 0, 3);
    $matNr = str_pad(strtoupper(base_convert($matNr, 10, 32)), 8, "0", STR_PAD_LEFT);
}
else {
    $error_404 = true;
}

if ($USER_TYPE == 'ato' && !isset($error_404)) {
    require_once(__DIR__ . '/inc/load_ato_data.inc.php');
} elseif ($USER_TYPE == 'participant' && !isset($error_404)) {
    require_once(__DIR__ . '/inc/load_participant_data.inc.php');
} else {
    $error_404 = true;
}
if (isset($error_404)) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/404.php');
    exit();
}
