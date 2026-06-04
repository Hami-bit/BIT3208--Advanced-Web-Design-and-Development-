<?php
// Week 4 - login.php
// Handles login form POST, validates credentials, starts session

session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($conn)) {
    if (isset($connection)) {
        $conn = $connection;
    } elseif (isset($db)) {
        $conn = $db;
    } else {
        die('Database connection not established.');
    }
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Receive form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 2. Basic validation
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // 3. Query database for user
        $query = "SELECT id, first_name, last_name, username, password, account_type, account_number, balance FROM users WHERE username = ?";
        $stmt  = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // 4. Verify password
            if (password_verify($password, $row['password'])) {
                // 5. Start session and store user data
                $_SESSION['user_id']      = $row['id'];
                $_SESSION['username']     = $row['username'];
                $_SESSION['full_name']    = $row['first_name'] . " " . $row['last_name'];
                $_SESSION['account_type'] = $row['account_type'];
                $_SESSION['account_number'] = $row['account_number'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexaBank - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand"> NexaBank</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to your NexaBank account</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" placeholder="Enter your username" autofocus>
                <span class="error-msg">Username is required</span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password">
                <span class="error-msg">Password is required</span>
            </div>

            <button type="submit" class="btn btn-success">Sign In</button>
        </form>

        <p class="form-link">Don't have an account? <a href="register.php">Create one</a></p>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
