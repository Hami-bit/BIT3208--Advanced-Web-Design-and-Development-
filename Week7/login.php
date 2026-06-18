<?php require_once __DIR__ . '/includes/header.php'; ?>

<main>
  <h1>Login</h1>
  <form action="authenticate.php" method="post">
    <label>Username<br><input type="text" name="username" required></label><br>
    <label>Password<br><input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p><a href="register.php">Create an account</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
