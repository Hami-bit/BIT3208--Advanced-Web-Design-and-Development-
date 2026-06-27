<?php
session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, first_name, last_name, status FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header('Location: login.php?error=1');
        exit;
    }

    if (isset($user['status']) && $user['status'] !== 'active') {
        header('Location: login.php?error=locked');
        exit;
    }

    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['full_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
        header('Location: dashboard.php');
        exit;
    }

    header('Location: login.php?error=1');
    exit;
}

header('Location: login.php');
exit;
