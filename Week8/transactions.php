<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Transactions';
require_once __DIR__ . '/includes/header.php';

$stmt = $conn->prepare('SELECT type, amount, balance_after, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 12');
$stmt->bind_param('i', $sessionUserId);
$stmt->execute();
$transactions = $stmt->get_result();
$stmt->close();
?>

<section class="table-card">
    <h1>Recent Transactions</h1>
    <p>View your latest activity from any device.</p>
    <table>
        <thead>
            <tr><th>Date</th><th>Description</th><th>Type</th><th>Amount</th><th>Balance After</th></tr>
        </thead>
        <tbody>
            <?php if ($transactions->num_rows > 0): ?>
                <?php while ($row = $transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))); ?></td>
                        <td><?php echo htmlspecialchars($row['description'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['type'] ?? ''); ?></td>
                        <td><?php echo ($row['type'] === 'deposit' ? '+' : '-'); ?> KES <?php echo number_format((float)($row['amount'] ?? 0), 2); ?></td>
                        <td>KES <?php echo number_format((float)($row['balance_after'] ?? 0), 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No transactions yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
