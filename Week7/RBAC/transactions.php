<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/session.php';

$user_id = $_SESSION['user_id'];

require_once __DIR__ . '/includes/header.php';
?>

<main>
  <h1>Create Transaction</h1>
  <form method="post" action="verify_transaction.php">
    <label>Type<br>
      <select name="type">
        <option value="deposit">Deposit</option>
        <option value="withdraw">Withdraw</option>
        <option value="transfer">Transfer</option>
      </select>
    </label><br>
    <label>Amount<br><input type="number" step="0.01" name="amount" required></label><br>
    <label>Target account (for transfer)<br><input type="text" name="target_account"></label><br>
    <button type="submit">Proceed</button>
  </form>

  <h2>Recent Transactions</h2>
  <table border="1" cellpadding="6" cellspacing="0">
    <thead><tr><th>ID</th><th>Type</th><th>Amount</th><th>Target</th><th>Status</th><th>When</th></tr></thead>
    <tbody>
    <?php
    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $stmt = $conn->prepare("SELECT id, type, amount, target_account, status, created_at FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 10");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($r['id'])."</td><td>".htmlspecialchars($r['type'])."</td><td>".htmlspecialchars($r['amount'])."</td><td>".htmlspecialchars($r['target_account'])."</td><td>".htmlspecialchars($r['status'])."</td><td>".htmlspecialchars($r['created_at'])."</td></tr>";
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT id, type, amount, target_account, status, created_at FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 10");
        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) {
            echo "<tr><td>".htmlspecialchars($r['id'])."</td><td>".htmlspecialchars($r['type'])."</td><td>".htmlspecialchars($r['amount'])."</td><td>".htmlspecialchars($r['target_account'])."</td><td>".htmlspecialchars($r['status'])."</td><td>".htmlspecialchars($r['created_at'])."</td></tr>";
        }
    }
    ?>
    </tbody>
  </table>

  <p><a href="dashboard.php">Back to Dashboard</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
