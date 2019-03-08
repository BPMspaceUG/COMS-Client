<?php
require_once(__DIR__ . '/inc/header.inc.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}