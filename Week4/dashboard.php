<?php
// Week 4 - dashboard.php
// Protected page - requires login session

require_once 'includes/auth.php';
require_once 'includes/db.php';

// Fetch fresh user balance from database
$query  = "SELECT balance, account_number, account_type FROM users WHERE id = ?";
$stmt   = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $sessionUserId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Fetch last 5 transactions
$txQuery  = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$txStmt   = mysqli_prepare($conn, $txQuery);
mysqli_stmt_bind_param($txStmt, "i", $sessionUserId);
mysqli_stmt_execute($txStmt);
$txResult = mysqli_stmt_get_result($txStmt);
mysqli_stmt_close($txStmt);

// Get initials for avatar
$nameParts = explode(" ", $sessionFullName);
$initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ""));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaBank - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">🏦 NexaBank</div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="deposit.php">Deposit</a></li>
            <li><a href="withdraw.php">Withdraw</a></li>
            <li><a href="transfer.php">Transfer</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-layout">

        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="avatar"><?php echo $initials; ?></div>
                <p><?php echo htmlspecialchars($sessionFullName); ?></p>
                <small><?php echo ucfirst(htmlspecialchars($user['account_type'])); ?> Account</small>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active"> Overview</a></li>
                <li><a href="deposit.php"> Deposit</a></li>
                <li><a href="withdraw.php"> Withdraw</a></li>
                <li><a href="transfer.php"> Transfer</a></li>
                <li><a href="transactions.php"> Transactions</a></li>
                <li><a href="profile.php"> My Profile</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Good day, <?php echo htmlspecialchars($nameParts[0]); ?> 👋</h1>
            <p class="page-subtitle">Account: <?php echo htmlspecialchars($user['account_number']); ?></p>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Current Balance</div>
                    <div class="stat-value">KES <?php echo number_format($user['balance'], 2); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📥</div>
                    <div class="stat-label">Total Deposits</div>
                    <?php
                    $dq = mysqli_query($conn, "SELECT COALESCE(SUM(amount),0) as total FROM transactions WHERE user_id=$sessionUserId AND type='deposit'");
                    $dr = mysqli_fetch_assoc($dq);
                    ?>
                    <div class="stat-value">KES <?php echo number_format($dr['total'], 2); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📤</div>
                    <div class="stat-label">Total Withdrawals</div>
                    <?php
                    $wq = mysqli_query($conn, "SELECT COALESCE(SUM(amount),0) as total FROM transactions WHERE user_id=$sessionUserId AND type='withdrawal'");
                    $wr = mysqli_fetch_assoc($wq);
                    ?>
                    <div class="stat-value">KES <?php echo number_format($wr['total'], 2); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔢</div>
                    <div class="stat-label">Account Type</div>
                    <div class="stat-value" style="font-size:1.2rem"><?php echo ucfirst($user['account_type']); ?></div>
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
                        <?php if (mysqli_num_rows($txResult) > 0): ?>
                            <?php while ($tx = mysqli_fetch_assoc($txResult)): ?>
                                <tr>
                                    <td><?php echo date("Y-m-d", strtotime($tx['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($tx['description']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $tx['type'] === 'deposit' ? 'success' : ($tx['type'] === 'withdrawal' ? 'danger' : 'info'); ?>">
                                            <?php echo ucfirst($tx['type']); ?>
                                        </span>
                                    </td>
                                    <td style="color: <?php echo $tx['type'] === 'deposit' ? '#38a169' : '#e53e3e'; ?>">
                                        <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?> KES <?php echo number_format($tx['amount'], 2); ?>
                                    </td>
                                    <td>KES <?php echo number_format($tx['balance_after'], 2); ?></td>
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

    <script src="js/main.js"></script>
</body>
</html>
