<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: transactions.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$type = $_POST['type'];
$amount = (float)$_POST['amount'];
$target = trim($_POST['target_account'] ?? '');

// basic validation
if ($amount <= 0) {
    header('Location: transactions.php?error=amount');
    exit;
}

// fetch user balance for withdraw/transfer check
if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$balance = (float)($user['balance'] ?? 0);
if (($type === 'withdraw' || $type === 'transfer') && $amount > $balance) {
    header('Location: transactions.php?error=insufficient');
    exit;
}

// store pending transaction in session and generate a simple confirmation token
$token = bin2hex(random_bytes(8));
$_SESSION['pending_tx'] = [
    'user_id' => $user_id,
    'type' => $type,
    'amount' => $amount,
    'target_account' => $target,
    'token' => $token,
    'created_at' => date('c')
];

require_once __DIR__ . '/includes/header.php';
?>

<main>
  <h1>Confirm Transaction</h1>
  <p>Type: <?php echo htmlspecialchars($type); ?></p>
  <p>Amount: <?php echo htmlspecialchars(number_format($amount,2)); ?></p>
  <?php if ($type === 'transfer'): ?>
    <p>Target Account: <?php echo htmlspecialchars($target); ?></p>
  <?php endif; ?>
  <form method="post" action="process_transaction.php">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <button type="submit">Confirm Transaction</button>
    <a href="transactions.php">Cancel</a>
  </form>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
