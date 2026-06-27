<?php
// Week 5 - transfer.php
// Full CRUD: READ receiver, UPDATE both balances, CREATE two transaction records

require_once 'includes/auth.php';
require_once 'includes/db.php';

$error   = "";
$success = "";

$balStmt = mysqli_prepare($conn, "SELECT balance, account_number FROM users WHERE id = ?");
mysqli_stmt_bind_param($balStmt, "i", $sessionUserId);
mysqli_stmt_execute($balStmt);
$currentUser = mysqli_fetch_assoc(mysqli_stmt_get_result($balStmt));
mysqli_stmt_close($balStmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiverAccount = trim($_POST['receiver_account']);
    $amount          = floatval($_POST['amount']);
    $note            = trim($_POST['note']) ?: 'Account transfer';

    if (empty($receiverAccount)) {
        $error = "Please enter the recipient account number.";
    } elseif ($receiverAccount === $currentUser['account_number']) {
        $error = "You cannot transfer to your own account.";
    } elseif ($amount <= 0) {
        $error = "Please enter a valid transfer amount.";
    } elseif ($amount > $currentUser['balance']) {
        $error = "Insufficient funds. Your balance is KES " . number_format($currentUser['balance'], 2);
    } else {
        // READ: Find receiver by account number
        $recStmt = mysqli_prepare($conn, "SELECT id, first_name, last_name FROM users WHERE account_number = ? AND status = 'active'");
        mysqli_stmt_bind_param($recStmt, "s", $receiverAccount);
        mysqli_stmt_execute($recStmt);
        $recResult = mysqli_stmt_get_result($recStmt);
        $receiver  = mysqli_fetch_assoc($recResult);
        mysqli_stmt_close($recStmt);

        if (!$receiver) {
            $error = "Recipient account not found or is inactive.";
        } else {
            mysqli_begin_transaction($conn);
            try {
                $senderNewBalance   = $currentUser['balance'] - $amount;
                $refNo              = "TXN-" . date("Ymd") . "-" . str_pad(rand(1,9999), 4, "0", STR_PAD_LEFT);
                $receiverName       = $receiver['first_name'] . " " . $receiver['last_name'];

                // UPDATE sender balance
                $s1 = mysqli_prepare($conn, "UPDATE users SET balance = ? WHERE id = ?");
                mysqli_stmt_bind_param($s1, "di", $senderNewBalance, $sessionUserId);
                mysqli_stmt_execute($s1);
                mysqli_stmt_close($s1);

                // UPDATE receiver balance
                $s2 = mysqli_prepare($conn, "UPDATE users SET balance = balance + ? WHERE id = ?");
                mysqli_stmt_bind_param($s2, "di", $amount, $receiver['id']);
                mysqli_stmt_execute($s2);
                mysqli_stmt_close($s2);

                // CREATE sender transaction
                $desc1 = "Transfer to " . $receiverName;
                $s3 = mysqli_prepare($conn, "INSERT INTO transactions (user_id, type, amount, balance_after, description, reference_no) VALUES (?, 'transfer', ?, ?, ?, ?)");
                mysqli_stmt_bind_param($s3, "iddss", $sessionUserId, $amount, $senderNewBalance, $desc1, $refNo);
                mysqli_stmt_execute($s3);
                mysqli_stmt_close($s3);

                // CREATE receiver transaction
                $s4q = "SELECT balance FROM users WHERE id = ?";
                $s4  = mysqli_prepare($conn, $s4q);
                mysqli_stmt_bind_param($s4, "i", $receiver['id']);
                mysqli_stmt_execute($s4);
                $recBalRow = mysqli_fetch_assoc(mysqli_stmt_get_result($s4));
                mysqli_stmt_close($s4);

                $desc2  = "Transfer from " . $sessionFullName;
                $refNo2 = "TXN-" . date("Ymd") . "-" . str_pad(rand(1,9999), 4, "0", STR_PAD_LEFT);
                $s5 = mysqli_prepare($conn, "INSERT INTO transactions (user_id, type, amount, balance_after, description, reference_no) VALUES (?, 'deposit', ?, ?, ?, ?)");
                mysqli_stmt_bind_param($s5, "iddss", $receiver['id'], $amount, $recBalRow['balance'], $desc2, $refNo2);
                mysqli_stmt_execute($s5);
                mysqli_stmt_close($s5);

                // CREATE transfer record
                $s6 = mysqli_prepare($conn, "INSERT INTO transfers (sender_id, receiver_id, amount, note) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($s6, "iids", $sessionUserId, $receiver['id'], $amount, $note);
                mysqli_stmt_execute($s6);
                mysqli_stmt_close($s6);

                mysqli_commit($conn);
                $success = " KES " . number_format($amount, 2) . " transferred to " . htmlspecialchars($receiverName) . " successfully!";
                $currentUser['balance'] = $senderNewBalance;

            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = "Transfer failed. Please try again.";
            }
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
    <title>NexaBank - Transfer</title>
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
                <li><a href="withdraw.php"> Withdraw</a></li>
                <li><a href="transfer.php" class="active"> Transfer</a></li>
                <li><a href="transactions.php"> Transactions</a></li>
                <li><a href="logout.php"> Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Transfer Funds</h1>
            <p class="page-subtitle">Send money to another NexaBank account</p>

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
                    <form method="POST" action="transfer.php" id="transactionForm">
                        <div class="form-group">
                            <label>Recipient Account Number</label>
                            <input type="text" name="receiver_account" placeholder="e.g. NXB-0003-2024">
                            <span class="error-msg">Account number is required</span>
                        </div>
                        <div class="form-group">
                            <label>Transfer Amount (KES)</label>
                            <input type="number" name="amount" id="amountInput" placeholder="0.00" min="1" step="0.01">
                            <span class="error-msg">Enter a valid amount</span>
                        </div>
                        <div class="form-group">
                            <label>Note (optional)</label>
                            <input type="text" name="note" placeholder="e.g. Rent payment">
                        </div>
                        <div style="padding:12px;background:#f4f7fb;border-radius:6px;margin-bottom:20px;font-size:0.9rem;color:#4a5568;">
                            Amount to transfer: <strong id="livePreview">KES 0.00</strong>
                        </div>
                        <button type="submit" class="btn btn-success" style="background:#2563a8;">Transfer Now</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>
