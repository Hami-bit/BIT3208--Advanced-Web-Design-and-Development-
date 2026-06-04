<?php
// Week 4 - register.php
// Handles registration form submission (POST processing)

session_start();
require_once 'includes/db.php';

$errors  = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Receive and sanitize form data
    $firstName   = trim($_POST['first_name']);
    $lastName    = trim($_POST['last_name']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $accountType = trim($_POST['account_type']);
    $username    = trim($_POST['username']);
    $password    = $_POST['password'];
    $confirm     = $_POST['confirm_password'];

    // 2. Server-side validation
    if (empty($firstName))   $errors[] = "First name is required.";
    if (empty($lastName))    $errors[] = "Last name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($username))    $errors[] = "Username is required.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm)  $errors[] = "Passwords do not match.";
    if (empty($accountType)) $errors[] = "Please select an account type.";

    // 3. Check if username or email already exists
    if (empty($errors)) {
        $checkQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Username or email is already taken.";
        }
        mysqli_stmt_close($stmt);
    }

    // 4. Insert new user if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $accountNumber  = "NXB-" . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT) . "-" . date("Y");
        $initialBalance = 0.00;

        $insertQuery = "INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number, balance)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssssssssd",
            $firstName, $lastName, $email, $phone,
            $accountType, $username, $hashedPassword,
            $accountNumber, $initialBalance
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "Account created successfully! Your account number is <strong>" . $accountNumber . "</strong>. <a href='login.php'>Sign in now</a>";
        } else {
            $errors[] = "Registration failed. Please try again.";
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
    <title>NexaBank - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-brand">🏦 NexaBank</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Open an Account</h2>
        <p class="subtitle">Join NexaBank today – free & secure</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err): ?>
                    <div>• <?php echo htmlspecialchars($err); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>" placeholder="Mike">
                    <span class="error-msg">First name is required</span>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>" placeholder="Milton">
                    <span class="error-msg">Last name is required</span>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" placeholder="mike@gmail.com">
                <span class="error-msg">Valid email is required</span>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" placeholder="+254 700 000 000">
            </div>

            <div class="form-group">
                <label>Account Type</label>
                <select name="account_type">
                    <option value="">-- Select Account Type --</option>
                    <option value="savings"  <?php echo (isset($accountType) && $accountType === 'savings')  ? 'selected' : ''; ?>>Savings Account</option>
                    <option value="current"  <?php echo (isset($accountType) && $accountType === 'current')  ? 'selected' : ''; ?>>Current Account</option>
                    <option value="fixed"    <?php echo (isset($accountType) && $accountType === 'fixed')    ? 'selected' : ''; ?>>Fixed Deposit</option>
                </select>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" placeholder="Choose a username">
                <span class="error-msg">Username is required</span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimum 8 characters">
                <span class="error-msg">Password must be at least 8 characters</span>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat your password">
                <span class="error-msg">Passwords do not match</span>
            </div>

            <button type="submit" class="btn btn-success">Create Account</button>
        </form>

        <p class="form-link">Already have an account? <a href="login.php">Sign In</a></p>
    </div>

    <script src="js/validation.js"></script>
</body>
</html>
