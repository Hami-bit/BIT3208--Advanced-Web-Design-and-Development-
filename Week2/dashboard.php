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

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-user">
                <div class="avatar">MM</div>
                <p>Mike Milton</p>
                <small>Savings Account</small>
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

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="page-title">Good morning, Mike </h1>
            <p class="page-subtitle">Here's your account overview</p>

            <!-- Stat Cards -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-label">Current Balance</div>
                    <div class="stat-value">KES 45,200</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📥</div>
                    <div class="stat-label">Total Deposits</div>
                    <div class="stat-value">KES 80,000</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📤</div>
                    <div class="stat-label">Total Withdrawals</div>
                    <div class="stat-value">KES 34,800</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔢</div>
                    <div class="stat-label">Account Number</div>
                    <div class="stat-value" style="font-size:1.2rem">NXB-001-2024</div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="table-card">
                <h3>Recent Transactions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-01-15</td>
                            <td>Cash Deposit</td>
                            <td><span class="badge badge-success">Deposit</span></td>
                            <td style="color: #38a169">+ KES 10,000</td>
                            <td>KES 45,200</td>
                        </tr>
                        <tr>
                            <td>2024-01-14</td>
                            <td>ATM Withdrawal</td>
                            <td><span class="badge badge-danger">Withdraw</span></td>
                            <td style="color: #e53e3e">- KES 2,500</td>
                            <td>KES 35,200</td>
                        </tr>
                        <tr>
                            <td>2024-01-13</td>
                            <td>Transfer to Alice</td>
                            <td><span class="badge badge-info">Transfer</span></td>
                            <td style="color: #e53e3e">- KES 5,000</td>
                            <td>KES 37,700</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
