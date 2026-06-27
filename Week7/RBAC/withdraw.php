<?php
// Week 5 - withdraw.php
// UPDATE + CREATE: Reduces balance and records withdrawal transaction

require_once 'includes/auth.php';
require_once 'includes/db.php';

$error   = "";
$success = "";

$balStmt = mysqli_prepare($conn, "SELECT balance FROM users WHERE id = ?");
mysqli_stmt_bind_param($balStmt, "i", $sessionUserId);
mysqli_stmt_execute($balStmt);
$currentUser = mysqli_fetch_assoc(mysqli_stmt_get_result($balStmt));
mysqli_stmt_close($balStmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount      = floatval($_POST['amount']);
    $description = trim($_POST['description']) ?: 'Cash withdrawal';

    if ($amount <= 0) {
        $error = "Please enter a valid withdrawal amount.";
    } elseif ($amount > $currentUser['balance']) {
        $error = "Insufficient funds. Your balance is KES " . number_format($currentUser['balance'], 2);
    } else {
        mysqli_begin_transaction($conn);
        try {
            $newBalance = $currentUser['balance'] - $amount;

            $updStmt = mysqli_prepare($conn, "UPDATE users SET balance = ? WHERE id = ?");
            mysqli_stmt_bind_param($updStmt, "di", $newBalance, $sessionUserId);
            mysqli_stmt_execute($updStmt);
            mysqli_stmt_close($updStmt);

            $refNo  = "TXN-" . date("Ymd") . "-" . str_pad(rand(1,9999), 4, "0", STR_PAD_LEFT);
            $txStmt = mysqli_prepare($conn, "INSERT INTO transactions (user_id, type, amount, balance_after, description, reference_no) VALUES (?, 'withdrawal', ?, ?, ?, ?)");
            mysqli_stmt_bind_param($txStmt, "iddss", $sessionUserId, $amount, $newBalance, $description, $refNo);
            mysqli_stmt_execute($txStmt);
            mysqli_stmt_close($txStmt);

            mysqli_commit($conn);
            $success = " KES " . number_format($amount, 2) . " withdrawn successfully! New balance: KES " . number_format($newBalance, 2);
            $currentUser['balance'] = $newBalance;

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Withdrawal failed. Please try again.";
        }
    }
}

$nameParts = explode(" ", $sessionFullName);
$initials  = strtoupper(substr($nameParts[0],0,1) . (isset($nameParts[1]) ? substr($nameParts[1],0,1) : ""));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaBank - Withdraw</title>
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
                <small>Balance: KES <?php echo number_format($currentUser['balance'], 2); ?></small>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php"> Overview</a></li>
                <li><a href="deposit.php"> Deposit</a></li>
                <li><a href="withdraw.php" class="active"> Withdraw</a></li>
                <li><a href="transfer.php"> Transfer</a></li>
                <li><a href="transactions.php"> Transactions</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Withdraw Funds</h1>
            <p class="page-subtitle">Withdraw money from your account</p>

            <div style="max-width:500px;">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="stat-card" style="margin-bottom:25px;">
                    <div class="stat-label">Available Balance</div>
                    <div class="stat-value">KES <?php echo number_format($currentUser['balance'], 2); ?></div>
                </div>

                <div class="table-card">
                    <form method="POST" action="withdraw.php" id="transactionForm">
                        <div class="form-group">
                            <label>Withdrawal Amount (KES)</label>
                            <input type="number" name="amount" id="amountInput" placeholder="0.00" min="1" step="0.01">
                            <span class="error-msg">Enter a valid amount</span>
                        </div>
                        <div class="form-group">
                            <label>Description (optional)</label>
                            <input type="text" name="description" placeholder="e.g. ATM withdrawal">
                        </div>
                        <div style="padding:12px;background:#f4f7fb;border-radius:6px;margin-bottom:20px;font-size:0.9rem;color:#4a5568;">
                            Amount to withdraw: <strong id="livePreview">KES 0.00</strong>
                        </div>
                        <button type="submit" class="btn btn-success" style="background:#e53e3e;">Withdraw Now</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>
