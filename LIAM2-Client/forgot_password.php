<?php
require_once(__DIR__ . '/inc/header.inc.php');
require_once(__DIR__ . '/inc/php-jwt-master/src/JWT.inc.php');
use \Firebase\JWT\JWT;

if (isset($_POST['forgot_password'])) {
    $email_input = htmlspecialchars($_POST['email']);
    $user_email = json_decode(api(json_encode(array(
        "cmd" => "read",
        "paramJS" => array(
            "table" => "liam2_User_email",
            "where" => "liam2_email_text = '$email_input' && a.state_id = 11"
        )
    ))), true);
    if (!$user_email) {
        $error = 'There is no registered user with this email address';
    } else {
        $result = json_decode(api(json_encode(array(
            "cmd" => "read",
            "paramJS" => array(
                "table" => "liam2_email",
                "where" => "liam2_email_text = '$email_input' && a.state_id = 14"
            )
        ))), true);
        if (!$result) {
            $error = 'This email address is not verified';
        }
    }
    if (!isset($error)) {
        $key = "liam2_key";
        $token = array(
            "iss" => "liam2",
            "aud" => $user_email[0]['liam2_User_id_fk_164887']['liam2_User_id'],
            "iat" => time(),
            "exp" => time() + 10800
        );

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($token, $key);

        $result = api(json_encode(array(
            "cmd" => "makeTransition",
            "paramJS" => array(
                "table" => "liam2_User",
                "row" => array(
                    "liam2_User_id" => $user_email[0]['liam2_User_id_fk_164887']['liam2_User_id'],
                    "liam2_client_passwd_reset" => true,
                    "jwt" => $jwt,
                    "liam2_User_email" => $email_input,
                    "state_id" => 9
                )
            )
        )));
        $result = json_decode($result, true);
        if ($result > 2) {
            $success = $result[2]['message'];
        }
        //var_dump($jwt);
    }
}
require_once(__DIR__ . '/inc/templates/forgot_password.inc.php');