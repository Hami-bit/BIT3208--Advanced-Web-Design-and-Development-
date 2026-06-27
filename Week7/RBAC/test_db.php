<?php
require_once __DIR__ . '/includes/db.php';

if ($conn) {
    echo "<p style='color:green;padding:20px;'> Connected to database: " . DB_NAME . "</p>";
} else {
    echo "<p style='color:red;padding:20px;'> Failed to connect.</p>";
}
?>
