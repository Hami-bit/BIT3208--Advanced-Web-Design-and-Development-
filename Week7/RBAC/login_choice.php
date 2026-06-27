<?php require_once __DIR__ . '/includes/header.php'; ?>

<main class="auth-choice">
  <section class="form-container">
    <h2>Welcome Back</h2>
    <p class="subtitle">Choose how you want to continue</p>

    <div class="choice-grid">
      <a href="login.php" class="choice-card">
        <div class="choice-icon">👤</div>
        <h3>Customer Login</h3>
        <p>Access your account, check balances, and manage transactions.</p>
        <span class="btn btn-primary">Continue as Customer</span>
      </a>

      <a href="admin_login.php" class="choice-card choice-card-admin">
        <div class="choice-icon">🛡️</div>
        <h3>Administrator Login</h3>
        <p>Secure access to manage users, approvals, and banking operations.</p>
        <span class="btn btn-outline">Continue as Admin</span>
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
