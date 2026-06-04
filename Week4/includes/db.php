<?php
// Week 4 - includes/db.php
// Central database connection file - imported by all pages

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'week4db');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("<p style='color:red;padding:20px;'>Database Connection Failed: " . mysqli_connect_error() . "</p>");
}

// Set character set
mysqli_set_charset($conn, "utf8mb4");
?>
