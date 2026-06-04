<?php
// Week 4 - includes/auth.php
// Session-based authentication guard
// Include this at the top of any protected page

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Make session data easily accessible
$sessionUser     = $_SESSION['username'];
$sessionUserId   = $_SESSION['user_id'];
$sessionFullName = $_SESSION['full_name'];
$sessionAccount  = $_SESSION['account_type'];
?>
