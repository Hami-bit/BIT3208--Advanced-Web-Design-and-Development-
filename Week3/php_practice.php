<?php
// Week 3 - PHP Syntax Practice - NexaBank
// Basic PHP concepts tested before Week 4 backend integration

// 1. Variables and data types
$bankName    = "NexaBank";
$interestRate = 3.5;
$isOpen      = true;
$yearFounded = 2024;

echo "<h2>Bank Info</h2>";
echo "<p>Name: " . $bankName . "</p>";
echo "<p>Interest Rate: " . $interestRate . "%</p>";
echo "<p>Open: " . ($isOpen ? "Yes" : "No") . "</p>";

// 2. Arrays
$accountTypes = ["Savings", "Current", "Fixed Deposit"];
echo "<h2>Account Types</h2><ul>";
foreach ($accountTypes as $type) {
    echo "<li>" . $type . "</li>";
}
echo "</ul>";

// 3. Associative Array
$user = [
    "name"     => "Mike Milton",
    "balance"  => 45200,
    "account"  => "Savings"
];
echo "<h2>Sample User</h2>";
echo "<p>Name: " . $user['name'] . "</p>";
echo "<p>Balance: KES " . number_format($user['balance']) . "</p>";

// 4. Functions
function formatCurrency($amount) {
    return "KES " . number_format($amount, 2);
}

echo "<p>Formatted: " . formatCurrency($user['balance']) . "</p>";

// 5. Conditional - simple auth check
$inputUsername = "admin";
$inputPassword = "password123";

if ($inputUsername === "admin" && $inputPassword === "password123") {
    echo "<p style='color:green'> Login would be allowed</p>";
} else {
    echo "<p style='color:red'> Invalid credentials</p>";
}

// 6. Basic database connection test (Week 3 practice)
$conn = @mysqli_connect("localhost", "root", "", "week3db");
if (!$conn) {
    echo "<p style='color:orange'>⚠️ Database not connected yet – set up in Week 4</p>";
} else {
    echo "<p style='color:green'> Database connected</p>";
}
?>
