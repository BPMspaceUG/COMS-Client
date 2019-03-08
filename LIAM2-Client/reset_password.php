<?php
require_once(__DIR__ . '/inc/header.inc.php');
require_once(__DIR__ . '/inc/php-jwt-master/src/BeforeValidException.inc.php');
require_once(__DIR__ . '/inc/php-jwt-master/src/ExpiredException.inc.php');
require_once(__DIR__ . '/inc/php-jwt-master/src/SignatureInvalidException.inc.php');
require_once(__DIR__ . '/inc/php-jwt-master/src/JWT.inc.php');
use \Firebase\JWT\JWT;
$show_form = true;
if (!isset($_GET['token'])) {
    $error = 'No token.';
    $show_form = false;
} else {
    $jwt = $_GET['token'];
    $key = "liam2_key";

    /**
     * You can add a leeway to account for when there is a clock skew times between
     * the signing and verifying servers. It is recommended that this leeway should
     * not be bigger than a few minutes.
     *
     * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
     */
    JWT::$leeway = 60; // $leeway in seconds
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    if (isset($error)) {
        $show_form = false;
    } else {
        $user_id = $decoded->aud;
        if (isset($_POST['liam2_reset_password'])) {
            $result = api(json_encode(array(
                "cmd" => "makeTransition",
                "paramJS" => array(
                    "table" => "liam2_User",
                    "row" => array(
                        "liam2_User_id" => $user_id,
                        "liam2_User_password_new" => htmlspecialchars($_POST['liam2_User_password_new']),
                        "liam2_User_password_new_confirm" => htmlspecialchars($_POST['liam2_User_password_new_confirm']),
                        "liam2_client_passwd_reset_form" => true,
                        "state_id" => 8
                    )
                )
            )));
            $result = json_decode($result, true);
            if (count($result) > 2) {
                $success = $result[0]['message'];
                $show_form = false;
            } else {
                $error = $result[0]['message'];
            }
        }
    }
}
require_once(__DIR__ . '/inc/templates/reset_password.inc.php');