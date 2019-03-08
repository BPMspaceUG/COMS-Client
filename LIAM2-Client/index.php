test liam
<?php
require_once(__DIR__ . '/inc/header.inc.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    $user = json_decode(api(json_encode(array(
        "cmd" => "read",
        "paramJS" => array(
            "table" => "liam2_User",
            "where" => "liam2_User_id = $_SESSION[user_id]"
        ))
    )), true);
    $username = $user[0]['liam2_User_firstname'] . ' ' . $user[0]['liam2_User_lastname'];
    require_once(__DIR__ . '/inc/templates/main.inc.php');
}
