<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Dashboard';
$user_id = (int)($_SESSION['user_id'] ?? 0);

$stmt = $conn->prepare("SELECT id, first_name, last_name, email, account_number, account_type, balance FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

$txQuery = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$txStmt = $conn->prepare($txQuery);
$txStmt->bind_param('i', $user_id);
$txStmt->execute();
$txResult = $txStmt->get_result();
$txStmt->close();

$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$nameParts = preg_split('/\s+/', $fullName) ?: ['Customer'];
$initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

require_once __DIR__ . '/includes/header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-user">
            <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
            <p><?php echo htmlspecialchars($fullName ?: 'Customer'); ?></p>
            <small><?php echo ucfirst(htmlspecialchars($user['account_type'] ?? 'savings')); ?> Account</small>
        </div>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php" class="active">Overview</a></li>
            <li><a href="deposit.php">Deposit</a></li>
            <li><a href="withdraw.php">Withdraw</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="transactions.php">Transactions</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <h1 class="page-title">Good day, <?php echo htmlspecialchars($nameParts[0]); ?></h1>
        <p class="page-subtitle">Account: <?php echo htmlspecialchars($user['account_number'] ?? ''); ?></p>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-label">Current Balance</div>
                <div class="stat-value">KES <?php echo number_format((float)($user['balance'] ?? 0), 2); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Account Type</div>
                <div class="stat-value" style="font-size: 1.1rem;"><?php echo ucfirst(htmlspecialchars($user['account_type'] ?? 'savings')); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Email</div>
                <div class="stat-value" style="font-size: 1rem;"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
            </div>
        </div>

        <div class="table-card">
            <h3>Recent Transactions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance After</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($txResult->num_rows > 0): ?>
                        <?php while ($tx = $txResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('Y-m-d', strtotime($tx['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($tx['description'] ?? ''); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($tx['type'] ?? '')); ?></td>
                                <td><?php echo ($tx['type'] === 'deposit' ? '+' : '-'); ?> KES <?php echo number_format((float)($tx['amount'] ?? 0), 2); ?></td>
                                <td>KES <?php echo number_format((float)($tx['balance_after'] ?? 0), 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;color:#718096;padding:30px;">No transactions yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
