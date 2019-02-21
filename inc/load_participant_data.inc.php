<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/captcha/captcha.inc.php');
if (isset($_POST['ato_logout'])) {
    session_destroy();
    header('Location: //' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['login'])) {
    if (file_exists($_POST['captcha-image'])) unlink($_POST['captcha-image']);
    if (!$_POST['booking-pw']) {
        $error = 'Please fill all the fields.';
    } else {
        $sentCode = htmlspecialchars($_POST["code"]);
        $result = (int)$_POST["result"];
        if (getExpressionResult($sentCode) !== $result) {
            $error = "Wrong Captcha.";
        } else {
            $bookingpw = hash("sha512", htmlspecialchars($_POST['booking-pw']) . $PARTID_MD5);
            $db_content = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email",
                "where" => "a.coms_participant_md5 = '$PARTID_MD5' && a.coms_participant_id = $PARTID"))), true);
            if ($db_content) {
                $_SESSION['participant_name'] = $db_content[0]['coms_participant_firstname'] . ' ' . $db_content[0]['coms_participant_lastname'];
                $_SESSION['user_id'] = $db_content[0]['coms_participant_id'];
                $_SESSION['user_type'] = $USER_TYPE;
            } else {
                $error = 'Wrong password';
            }
        }
    }
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $PARTID) {
    generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/templates/login.inc.php');
} else {
    var_dump($_SESSION);
}