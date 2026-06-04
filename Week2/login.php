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

        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter your username">
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

    <script src="js/main.js"></script>
</body>
</html>
