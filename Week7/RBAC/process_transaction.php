<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: transactions.php');
    exit;
}

$token = $_POST['token'] ?? '';
$pending = $_SESSION['pending_tx'] ?? null;
if (!$pending || !hash_equals($pending['token'], $token)) {
    header('Location: transactions.php?error=token');
    exit;
}

$user_id = $pending['user_id'];
$type = $pending['type'];
$amount = (float)$pending['amount'];
$target = $pending['target_account'];

try {
    // start transaction
    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $conn->begin_transaction();
        // update balance depending on type
        if ($type === 'deposit') {
            $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->bind_param('di', $amount, $user_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($type === 'withdraw') {
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->bind_param('di', $amount, $user_id);
            $stmt->execute();
            $stmt->close();
        } elseif ($type === 'transfer') {
            // deduct from sender
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->bind_param('di', $amount, $user_id);
            $stmt->execute();
            $stmt->close();
            // credit to target account if exists
            if (!empty($target)) {
                $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE account_number = ?");
                $stmt->bind_param('ds', $amount, $target);
                $stmt->execute();
                $stmt->close();
            }
        }

        // insert transaction record
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, target_account, status) VALUES (?, ?, ?, ?, 'completed')");
        $stmt->bind_param('isds', $user_id, $type, $amount, $target);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
    } else {
        $conn->beginTransaction();
        if ($type === 'deposit') {
            $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);
        } elseif ($type === 'withdraw') {
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);
        } elseif ($type === 'transfer') {
            $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);
            if (!empty($target)) {
                $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE account_number = ?");
                $stmt->execute([$amount, $target]);
            }
        }
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, target_account, status) VALUES (?, ?, ?, ?, 'completed')");
        $stmt->execute([$user_id, $type, $amount, $target]);
        $conn->commit();
    }
    // clear pending
    unset($_SESSION['pending_tx']);
    header('Location: transactions.php?success=1');
    exit;
} catch (Exception $e) {
    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $conn->rollback();
    } else {
        $conn->rollBack();
    }
    header('Location: transactions.php?error=process');
    exit;
}
