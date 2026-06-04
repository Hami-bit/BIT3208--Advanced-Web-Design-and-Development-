<?php
// Week 1 - Hello World & Environment Test
// BIT3208 Banking System - Advanced Web Design and Development

echo "<h1>Hello World - NexaBank System</h1>";
echo "<p>Localhost is working correctly!</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaBank - Week 1 Setup</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>NexaBank System</h1>
        <p>Week 1: Environment Setup Complete</p>
        <ul>
            <li> XAMPP/Laragon installed</li>
            <li> PHP is running</li>
            <li> Localhost is working</li>
            <li> Hello World page created</li>
        </ul>
        <a href="database/test_connection.php">Test Database Connection</a>
    </div>
</body>
</html>
