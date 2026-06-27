<?php
require_once __DIR__ . '/includes/admin_auth.php';
admin_require_login();
require_once __DIR__ . '/includes/header.php';

$role = $_SESSION['admin_role'] ?? 'normal';
$actions = [
    ['title' => 'Manage Users', 'desc' => 'Review customer accounts and permissions.', 'allowed' => ['super', 'manager', 'normal']],
    ['title' => 'Approve Transfers', 'desc' => 'Review suspicious or high-value transfer requests.', 'allowed' => ['super', 'manager']],
    ['title' => 'Manage Admins', 'desc' => 'Create or update admin accounts and roles.', 'allowed' => ['super']],
    ['title' => 'View Reports', 'desc' => 'Inspect activity and account summaries.', 'allowed' => ['super', 'manager', 'normal']],
];
?>

<main class="auth-choice">
  <section class="form-container" style="max-width: 760px;">
    <h2>Admin Dashboard</h2>
    <p class="subtitle">Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin'); ?></p>
    <p style="text-align:center; margin-bottom: 24px;">Your role: <strong><?= htmlspecialchars($role); ?></strong></p>

    <div class="choice-grid">
      <?php foreach ($actions as $action): ?>
        <?php if (in_array($role, $action['allowed'], true)): ?>
          <div class="choice-card">
            <h3><?= htmlspecialchars($action['title']); ?></h3>
            <p><?= htmlspecialchars($action['desc']); ?></p>
            <span class="btn btn-primary">Open</span>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div style="margin-top: 24px; text-align: center;">
      <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>