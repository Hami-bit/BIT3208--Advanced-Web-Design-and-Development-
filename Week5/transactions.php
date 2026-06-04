<?php
// Week 5 - transactions.php
// READ operation: Displays full transaction history for logged-in user

require_once 'includes/auth.php';
require_once 'includes/db.php';

// Pagination
$perPage     = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset      = ($currentPage - 1) * $perPage;

// Count total records
$countStmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM transactions WHERE user_id = ?");
mysqli_stmt_bind_param($countStmt, "i", $sessionUserId);
mysqli_stmt_execute($countStmt);
$countRow   = mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt));
$totalRows  = $countRow['total'];
$totalPages = ceil($totalRows / $perPage);
mysqli_stmt_close($countStmt);

// Fetch transactions with pagination
$txStmt = mysqli_prepare($conn, "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($txStmt, "iii", $sessionUserId, $perPage, $offset);
mysqli_stmt_execute($txStmt);
$txResult = mysqli_stmt_get_result($txStmt);
mysqli_stmt_close($txStmt);

// Get current balance
$balStmt = mysqli_prepare($conn, "SELECT balance FROM users WHERE id = ?");
mysqli_stmt_bind_param($balStmt, "i", $sessionUserId);
mysqli_stmt_execute($balStmt);
$balRow = mysqli_fetch_assoc(mysqli_stmt_get_result($balStmt));
mysqli_stmt_close($balStmt);

$nameParts = explode(" ", $sessionFullName);
$initials  = strtoupper(substr($nameParts[0],0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : ""));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaBank - Transaction History</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand"> NexaBank</div>
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
                <small>Balance: KES <?php echo number_format($balRow['balance'], 2); ?></small>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php"> Overview</a></li>
                <li><a href="deposit.php"> Deposit</a></li>
                <li><a href="withdraw.php"> Withdraw</a></li>
                <li><a href="transfer.php"> Transfer</a></li>
                <li><a href="transactions.php" class="active"> Transactions</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Transaction History</h1>
            <p class="page-subtitle">All your account activity – <?php echo $totalRows; ?> records found</p>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reference</th>
                            <th>Date & Time</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($txResult) > 0): ?>
                            <?php $num = $offset + 1; ?>
                            <?php while ($tx = mysqli_fetch_assoc($txResult)): ?>
                                <tr>
                                    <td><?php echo $num++; ?></td>
                                    <td style="font-size:0.8rem;color:#718096;"><?php echo htmlspecialchars($tx['reference_no']); ?></td>
                                    <td style="font-size:0.85rem;"><?php echo date("d M Y, H:i", strtotime($tx['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($tx['description']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $tx['type'] === 'deposit' ? 'success' : ($tx['type'] === 'withdrawal' ? 'danger' : 'info'); ?>">
                                            <?php echo ucfirst($tx['type']); ?>
                                        </span>
                                    </td>
                                    <td style="color:<?php echo $tx['type'] === 'deposit' ? '#38a169' : '#e53e3e'; ?>;font-weight:600;">
                                        <?php echo $tx['type'] === 'deposit' ? '+' : '-'; ?> KES <?php echo number_format($tx['amount'], 2); ?>
                                    </td>
                                    <td>KES <?php echo number_format($tx['balance_after'], 2); ?></td>
                                    <td><span class="badge badge-success"><?php echo ucfirst($tx['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align:center;padding:40px;color:#718096;">No transactions found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div style="display:flex;gap:8px;margin-top:20px;justify-content:center;">
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <a href="?page=<?php echo $p; ?>" style="padding:8px 14px;border-radius:5px;text-decoration:none;background:<?php echo $p === $currentPage ? '#1a3c5e' : '#f0f4f8'; ?>;color:<?php echo $p === $currentPage ? '#fff' : '#1a3c5e'; ?>;font-size:0.9rem;"><?php echo $p; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <script src="js/main.js"></script>
</body>
</html>
