<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$sessionUserId = (int)($_SESSION['user_id'] ?? 0);
$sessionUsername = $_SESSION['username'] ?? '';
$sessionFullName = $_SESSION['full_name'] ?? '';
$sessionAccountType = $_SESSION['account_type'] ?? 'savings';
?>
