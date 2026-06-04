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
        <div class="nav-brand"> NexaBank</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Open an Account</h2>
        <p class="subtitle">Join NexaBank today – free & secure</p>

        <form method="POST" action="register.php" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" placeholder="Mike">
                    <span class="error-msg">First name is required</span>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" placeholder="Milton">
                    <span class="error-msg">Last name is required</span>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="mike@gmail.com">
                <span class="error-msg">Valid email is required</span>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="+254 700 000 000">
            </div>

            <div class="form-group">
                <label>Account Type</label>
                <select name="account_type">
                    <option value="">-- Select Account Type --</option>
                    <option value="savings">Savings Account</option>
                    <option value="current">Current Account</option>
                    <option value="fixed">Fixed Deposit</option>
                </select>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username">
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

    <script src="js/main.js"></script>
</body>
</html>
