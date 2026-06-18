<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$user_id = $_SESSION['user_id'];
// fetch fresh user info
if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, account_number, balance FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, account_number, balance FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

require_once __DIR__ . '/includes/header.php';
?>

<main>
  <h1>Dashboard</h1>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] ?? $user['first_name']); ?>!</p>
  <ul>
    <li>Full name: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></li>
    <li>Email: <?php echo htmlspecialchars($user['email']); ?></li>
    <li>Account: <?php echo htmlspecialchars($user['account_number']); ?></li>
    <li>Balance: <?php echo htmlspecialchars(number_format($user['balance'],2)); ?></li>
  </ul>
  <p><a href="logout.php">Logout</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
