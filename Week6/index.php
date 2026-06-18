<?php
require_once __DIR__ . '/includes/db.php';

// fetch rows using mysqli or PDO
$rows = [];
if (isset($conn) && (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli))) {
  $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
  while ($r = $result->fetch_assoc()) { $rows[] = $r; }
} else {
  $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

require_once __DIR__ . '/includes/header.php';
?>

  <main>
    <h1>Week6 — User Records (CRUD Demo)</h1>
    <p><a href="register.php">Add New User</a></p>
    <table border="1" cellpadding="6" cellspacing="0">
      <thead>
        <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Account No</th><th>Balance</th><th>Actions</th></tr>
      </thead>
      <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['first_name']); ?></td>
              <td><?php echo htmlspecialchars($row['last_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['account_number'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row['balance'] ?? '0.00'); ?></td>
            <td>
              <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
              <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
