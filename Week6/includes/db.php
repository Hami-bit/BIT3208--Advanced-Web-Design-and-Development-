<?php
$servername = "localhost";
$username = "root";
$password = ""; // update if you use a different MySQL password
$dbname = "week6db";

// Prefer mysqli when available, otherwise use PDO
if (class_exists('mysqli')) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} else {
    try {
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
        $conn = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Optional: uncomment to debug successful connection
// echo "Connected to $dbname";

?>
