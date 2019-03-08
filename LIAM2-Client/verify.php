<?php
require_once(__DIR__ . '/inc/header.inc.php');
if (!isset($_GET['email_id'])) {
    $error = 'No email id.';
} else {
    $email_id = $_GET['email_id'];
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
            $success = 'Success.';
        } else {
            $error = 'This email is already verified or blocked.';
        }
    }
}
require_once(__DIR__ . '/inc/templates/verify.inc.php');