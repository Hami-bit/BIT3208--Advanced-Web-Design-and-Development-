<?php
$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $accountType = trim($_POST['account_type'] ?? 'savings');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($firstName === '' || $lastName === '' || $email === '' || $username === '' || $password === '') {
        $error = 'Please complete all required fields.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $accountNumber = 'NXB-' . date('Ymd') . '-' . rand(1000, 9999);
        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, phone, account_type, username, password, account_number, balance, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0.00, "active")');
        $stmt->bind_param('ssssssss', $firstName, $lastName, $email, $phone, $accountType, $username, $hash, $accountNumber);
        if ($stmt->execute()) {
            $success = 'Account created successfully. You can now sign in.';
        } else {
            $error = 'Unable to create the account. Please try again.';
        }
        $stmt->close();
    }
}
?>

<section class="form-card">
    <h1>Create Account</h1>
    <p>Join NexaBank with a simple, responsive sign-up form.</p>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <form method="post" action="register.php">
        <div class="form-group">
            <label for="first_name">First name</label>
            <input id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last name</label>
            <input id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input id="phone" name="phone">
        </div>
        <div class="form-group">
            <label for="account_type">Account Type</label>
            <select id="account_type" name="account_type">
                <option value="savings">Savings</option>
                <option value="current">Current</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <button class="btn btn-primary" type="submit">Create Account</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
