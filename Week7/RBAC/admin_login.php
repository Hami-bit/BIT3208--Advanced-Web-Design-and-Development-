<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/db.php';

$error = $_GET['error'] ?? '';
?>

<main class="auth-choice">
  <section class="form-container">
    <h2>Admin Portal</h2>
    <p class="subtitle">Sign in with your administrator credentials</p>

    <?php if ($error === 'invalid'): ?>
      <div class="error-message">Invalid username or password.</div>
    <?php elseif ($error === 'locked'): ?>
      <div class="error-message">This administrator account is temporarily locked.</div>
    <?php endif; ?>

    <form action="admin_authenticate.php" method="post" class="form-group">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-success">Login as Admin</button>
    </form>

    <p style="margin-top: 16px; text-align: center;">
      <a href="login_choice.php" class="link-muted">Back to role selection</a>
    </p>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>