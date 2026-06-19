<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/session.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // fetch user including status/lock fields
    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $stmt = $conn->prepare("SELECT id, password, first_name, last_name, status, failed_login_attempts, locked_until FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT id, password, first_name, last_name, status, failed_login_attempts, locked_until FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // check existence
    if (!$user) {
        header('Location: login.php?error=1');
        exit;
    }

    // check account status
    if (isset($user['status']) && $user['status'] !== 'active') {
        header('Location: login.php?error=locked');
        exit;
    }

    // check locked_until
    if (!empty($user['locked_until'])) {
        $lockedUntil = new DateTime($user['locked_until']);
        $now = new DateTime();
        if ($lockedUntil > $now) {
            header('Location: login.php?error=locked');
            exit;
        }
    }

    // verify password
    if ($user && password_verify($password, $user['password'])) {
        // successful: reset counters, regenerate session id
        if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ?");
            $stmt->bind_param('i', $user['id']);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        // After successful login in Week7 redirect user to Week5 dashboard
        header('Location: /NexaBank_Week5/dashboard.php');
        exit;
    }

    // failed: increment counter and possibly lock account
    $attempts = (int)($user['failed_login_attempts'] ?? 0) + 1;
    $lockUntil = null;
    $maxAttempts = 5;
    if ($attempts >= $maxAttempts) {
        $lockMinutes = 15;
        $dt = new DateTime();
        $dt->modify("+{$lockMinutes} minutes");
        $lockUntil = $dt->format('Y-m-d H:i:s');
    }

    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        if ($lockUntil) {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = ?, locked_until = ? WHERE id = ?");
            $stmt->bind_param('isi', $attempts, $lockUntil, $user['id']);
        } else {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = ? WHERE id = ?");
            $stmt->bind_param('ii', $attempts, $user['id']);
        }
        $stmt->execute();
        $stmt->close();
    } else {
        if ($lockUntil) {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = ?, locked_until = ? WHERE id = ?");
            $stmt->execute([$attempts, $lockUntil, $user['id']]);
        } else {
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = ? WHERE id = ?");
            $stmt->execute([$attempts, $user['id']]);
        }
    }

    header('Location: login.php?error=1');
    exit;
}

header('Location: login.php');
exit;
