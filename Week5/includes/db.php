<?php
// Week 5 - includes/db.php
// Central database connection file - imported by all pages

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'week5db');

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS);

if (!$conn) {
    die("<p style='color:red;padding:20px;'>Database Connection Failed: " . mysqli_connect_error() . "</p>");
}

if (!mysqli_select_db($conn, DB_NAME)) {
    $createDb = mysqli_query($conn, 'CREATE DATABASE IF NOT EXISTS ' . DB_NAME);
    if (!$createDb) {
        die("<p style='color:red;padding:20px;'>Database Creation Failed: " . mysqli_error($conn) . "</p>");
    }
    if (!mysqli_select_db($conn, DB_NAME)) {
        die("<p style='color:red;padding:20px;'>Unable to select database: " . DB_NAME . "</p>");
    }
}

$tables = [
    'users' => "
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            phone VARCHAR(20),
            account_type ENUM('savings','current','fixed') DEFAULT 'savings',
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            account_number VARCHAR(30) NOT NULL UNIQUE,
            balance DECIMAL(15,2) DEFAULT 0.00,
            status ENUM('active','suspended','closed') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
    'transactions' => "
        CREATE TABLE IF NOT EXISTS transactions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            type ENUM('deposit','withdrawal','transfer') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            balance_after DECIMAL(15,2) NOT NULL,
            description VARCHAR(255),
            reference_no VARCHAR(50) UNIQUE,
            status ENUM('success','failed','pending') DEFAULT 'success',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
    'transfers' => "
        CREATE TABLE IF NOT EXISTS transfers (
            id INT PRIMARY KEY AUTO_INCREMENT,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            note VARCHAR(255),
            status ENUM('success','failed') DEFAULT 'success',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users(id),
            FOREIGN KEY (receiver_id) REFERENCES users(id)
        )"
];

foreach ($tables as $name => $sql) {
    if (!mysqli_query($conn, $sql)) {
        die("<p style='color:red;padding:20px;'>Failed to create table {$name}: " . mysqli_error($conn) . "</p>");
    }
}

$seedUsers = mysqli_query($conn, "
INSERT IGNORE INTO users (first_name, last_name, email, phone, account_type, username, password, account_number, balance)
VALUES
('Admin', 'NexaBank', 'admin@nexabank.com', '+254700000001', 'current', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'NXB-0001-2024', 100000.00),
('Mike', 'Milton', 'mike@gmail.com', '+254700000002', 'savings', 'mike', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'NXB-0002-2024', 45200.00),
('Alice', 'Wanjiru', 'alice@example.com', '+254700000003', 'savings', 'alice', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'NXB-0003-2024', 22000.00)");
if (!$seedUsers) {
    die("<p style='color:red;padding:20px;'>Failed to seed users: " . mysqli_error($conn) . "</p>");
}

$seedTransactions = mysqli_query($conn, "
INSERT IGNORE INTO transactions (user_id, type, amount, balance_after, description, reference_no)
VALUES
(2, 'deposit', 50000.00, 50000.00, 'Initial deposit', 'TXN-20240101-001'),
(2, 'deposit', 10000.00, 60000.00, 'Cash deposit at branch', 'TXN-20240110-002'),
(2, 'withdrawal', 5000.00, 55000.00, 'ATM withdrawal', 'TXN-20240112-003'),
(2, 'transfer', 9800.00, 45200.00, 'Transfer to Alice', 'TXN-20240115-004'),
(3, 'deposit', 30000.00, 30000.00, 'Initial deposit', 'TXN-20240101-005'),
(3, 'deposit', 9800.00, 39800.00, 'Transfer from Mike', 'TXN-20240115-006'),
(3, 'withdrawal', 17800.00, 22000.00, 'ATM withdrawal', 'TXN-20240116-007')");
if (!$seedTransactions) {
    die("<p style='color:red;padding:20px;'>Failed to seed transactions: " . mysqli_error($conn) . "</p>");
}

mysqli_set_charset($conn, "utf8mb4");
?>
