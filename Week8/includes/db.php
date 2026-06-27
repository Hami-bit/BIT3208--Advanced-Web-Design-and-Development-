<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'nexabankdb';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('<div style="padding:1rem;color:#b91c1c;">Database connection failed. Please start MySQL and ensure the NexaBank database exists.</div>');
}

mysqli_set_charset($conn, 'utf8mb4');
?>
