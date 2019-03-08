<?php
require_once(__DIR__ . '/inc/header.inc.php');
$show_form = false;
if (!isset($_GET['email_id'])) {
    $error = 'No email id.';
} else {
    $email_id = $_GET['email_id'];
    if (isset($_POST['register'])) {
        $password = trim(htmlspecialchars($_POST['password']));
        $result = api(json_encode(array(
                "cmd" => "create",
                "paramJS" => array(
                    "table" => "liam2_User",
                    "row" => array(
                        "liam2_User_firstname" => htmlspecialchars($_POST['firstname']),
                        "liam2_User_lastname" => htmlspecialchars($_POST['lastname']),
                        "liam2_User_password" => $password,
                        "liam2_User_email_id" => $email_id
                    )
                )
            )
        ));
        $result = json_decode($result, true);
        if (count($result) > 1) {
            $success = 'Success.';
        } else {
            $error = $result[0]['message'];
            $show_form = true; ?>
        <?php }
    } else {
        $result = api(json_encode(array(
                "cmd" => "makeTransition",
                "paramJS" => array(
                    "table" => "liam2_email",
                    "row" => array(
                        "liam2_email_id" => $email_id,
                        "state_id" => 14
                    )
                )
            )
        ));
        try {
            $result = json_decode($result, true);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        if (!isset($error)) {
            if ($result && count($result) > 2) {
                $show_form = true;
            } else {
                $error = 'This email is already verified or blocked.';
            }
        }
    }
}
require_once(__DIR__ . '/inc/templates/register.inc.php');