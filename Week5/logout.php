<?php
// Week 4 - logout.php
// Destroys session and redirects to login

session_start();
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
