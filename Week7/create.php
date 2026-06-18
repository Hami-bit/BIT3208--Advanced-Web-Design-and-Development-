<?php
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $account_type = $_POST['account_type'] ?? 'savings';
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $account_number = 'NXB-' . str_pad(rand(1,9999),4,'0',STR_PAD_LEFT) . '-' . date('Y');
    $hashed = password_hash($password, PASSWORD_BCRYPT);

    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssss', $first_name, $last_name, $email, $phone, $account_type, $username, $hashed, $account_number);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $phone, $account_type, $username, $hashed, $account_number]);
    }

    header('Location: login.php');
    exit;
}

header('Location: register.php');
exit;
