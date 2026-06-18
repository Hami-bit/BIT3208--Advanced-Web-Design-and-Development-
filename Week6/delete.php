<?php
require_once __DIR__ . '/includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
