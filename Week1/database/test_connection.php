<?php
// Week 1 - Database Connectivity Test
// Tests that MySQL connection is working

$conn = mysqli_connect("localhost", "root", "", "");

if (!$conn) {
    echo "<p style='color:red;'> Connection Failed: " . mysqli_connect_error() . "</p>";
} else {
    echo "<p style='color:green;'> Database connection successful! MySQL is running.</p>";
    mysqli_close($conn);
}
?>
