<?php
require_once(__DIR__ . '/inc/header.inc.php');
require_once(__DIR__ . '/inc/captcha/captcha.inc.php');
generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
if (isset($_POST['liam2_login'])) {
    if (file_exists($_POST['captcha-image'])) unlink($_POST['captcha-image']);
    if (!$_POST['email'] || !$_POST['password']) {
        $error = 'Please fill all the fields.';
    } else {
        $email_input = htmlspecialchars($_POST['email']);
        $password_input = htmlspecialchars($_POST['password']);
        $sentCode = htmlspecialchars($_POST['code']);
        $captcha_result = (int)$_POST['result'];
        if (getExpressionResult($sentCode) !== $captcha_result) {
            $error = 'Wrong Captcha.';
            $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
            $result = api(json_encode(array(
                    "cmd" => "create",
                    "paramJS" => array(
                        "table" => "liam2_LoginAttempts",
                        "row" => array(
                            "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                            "liam2_LoginAttempts_info" => $login_attempt_info
                        )
                    )
                )
            ));
        } else {
            $error = false;
            $email = json_decode(api(json_encode(array("cmd" => "read", "paramJS" => array("table" => "liam2_email",
                "where" => "liam2_email_text = '$email_input'")))), true);
            if (!$email) {
                $error = 'Wrong email';
                $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                $result = api(json_encode(array(
                        "cmd" => "create",
                        "paramJS" => array(
                            "table" => "liam2_LoginAttempts",
                            "row" => array(
                                "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                "liam2_LoginAttempts_info" => $login_attempt_info
                            )
                        )
                    )
                ));
            } elseif ($email[0]['state_id']['state_id'] == 14) {
                $email_id = $email[0]['liam2_email_id'];
            } else {
                $error = 'Email is not verified';
                $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                $result = api(json_encode(array(
                        "cmd" => "create",
                        "paramJS" => array(
                            "table" => "liam2_LoginAttempts",
                            "row" => array(
                                "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                "liam2_LoginAttempts_info" => $login_attempt_info
                            )
                        )
                    )
                ));
            }
            if (!$error) {
                $user_email = json_decode(api(json_encode(array("cmd" => "read", "paramJS" => array("table" => "liam2_User_email",
                    "where" => "liam2_email_id = $email_id")))), true);
                if ($user_email && ($user_email[0]['state_id']['state_id'] == 11)) {
                    $user_id = $user_email[0]['liam2_User_id_fk_164887']['liam2_User_id'];
                } else {
                    $error = 'This email is unselected.';
                    $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                    $result = api(json_encode(array(
                            "cmd" => "create",
                            "paramJS" => array(
                                "table" => "liam2_LoginAttempts",
                                "row" => array(
                                    "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                    "liam2_LoginAttempts_info" => $login_attempt_info
                                )
                            )
                        )
                    ));
                }
            }
            if (!$error) {
                $user = json_decode(api(json_encode(array("cmd" => "read", "paramJS" => array("table" => "liam2_User",
                    "where" => "liam2_User_id = $user_id")))), true);
                if (!$user) {
                    $error = 'This email is not linked to any user.';
                    $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                    $result = api(json_encode(array(
                            "cmd" => "create",
                            "paramJS" => array(
                                "table" => "liam2_LoginAttempts",
                                "row" => array(
                                    "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                    "liam2_LoginAttempts_info" => $login_attempt_info
                                )
                            )
                        )
                    ));
                }
                if (!$error && ($user[0]['state_id']['state_id'] != 8)) {
                    $error = 'The state of this user is not complete';
                    $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                    $result = api(json_encode(array(
                            "cmd" => "create",
                            "paramJS" => array(
                                "table" => "liam2_LoginAttempts",
                                "row" => array(
                                    "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                    "liam2_LoginAttempts_info" => $login_attempt_info
                                )
                            )
                        )
                    ));
                }
            }
            if (!$error) {
                $salt = $user[0]['liam2_User_salt'];
                $hashedPassword = hash('sha512', $password_input . $salt);
                if ($hashedPassword != $user[0]['liam2_User_password']) {
                    $error = 'Wrong password.';
                    $login_attempt_info = 'Not Successful - ' . $email_input . ' - ' . $error;
                    $result = api(json_encode(array(
                            "cmd" => "create",
                            "paramJS" => array(
                                "table" => "liam2_LoginAttempts",
                                "row" => array(
                                    "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                    "liam2_LoginAttempts_info" => $login_attempt_info
                                )
                            )
                        )
                    ));
                } else {
                    $_SESSION['user_id'] = $user_id;
                    $login_attempt_info = 'Successful - ' . $email_input;
                    $result = api(json_encode(array(
                            "cmd" => "create",
                            "paramJS" => array(
                                "table" => "liam2_LoginAttempts",
                                "row" => array(
                                    "liam2_LoginAttempts_time" => date('Y-m-d H:i'),
                                    "liam2_LoginAttempts_info" => $login_attempt_info
                                )
                            )
                        )
                    ));
                }
            }
        }
    }
}
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
} else {
    require_once(__DIR__ . '/inc/templates/login.inc.php');
}