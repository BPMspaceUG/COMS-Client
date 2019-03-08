<?php
require_once(__DIR__ . '/inc/header.inc.php');
require_once(__DIR__ . '/inc/captcha/captcha.inc.php');
if (isset($_POST['self_register'])) {
    if (file_exists($_POST['captcha-image'])) unlink($_POST['captcha-image']);
    $sentCode = htmlspecialchars($_POST['code']);
    $result = (int)$_POST['result'];
    if (getExpressionResult($sentCode) !== $result) {
        $error = 'Wrong Captcha.';
    } else {
        $result = api(json_encode(array(
                "cmd" => "create",
                "paramJS" => array(
                    "table" => "liam2_email",
                    "row" => array(
                        "liam2_email_text" => htmlspecialchars($_POST['email'])
                    )
                )
            )
        ));
        $result = json_decode($result, true);
        if (count($result) > 1) {
            $success = 'A verification link has been sent to your email address.';
        } else {
            $error = $result[0]['message'];
        }
    }
}
generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
require_once(__DIR__ . '/inc/templates/self_register.inc.php');