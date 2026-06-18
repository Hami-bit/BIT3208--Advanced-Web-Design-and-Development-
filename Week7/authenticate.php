<?php
require_once __DIR__ . '/includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $stmt = $conn->prepare("SELECT id, password, first_name, last_name FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT id, password, first_name, last_name FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($user && password_verify($password, $user['password'])) {
        // successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        header('Location: dashboard.php');
        exit;
    }

    // failed
    header('Location: login.php?error=1');
    exit;
}

header('Location: login.php');
exit;
