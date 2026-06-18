<?php
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $account_type = $_POST['account_type'] ?? 'savings';
    $username = trim($_POST['username']);

    if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, account_type=?, username=? WHERE id=?");
        $stmt->bind_param('ssssssi', $first_name, $last_name, $email, $phone, $account_type, $username, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, account_type=?, username=? WHERE id=?");
        $stmt->execute([$first_name, $last_name, $email, $phone, $account_type, $username, $id]);
    }

    header('Location: index.php');
    exit;
}

// GET — show form
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
if (get_class($conn) === 'mysqli' || (class_exists('mysqli') && $conn instanceof mysqli)) {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone, account_type, username FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone, account_type, username FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$student) {
    echo "User not found.";
    exit;
}

?>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<main>
  <h1>Edit Bank User</h1>
  <form method="post" action="edit.php">
    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
    <label>First name<br><input type="text" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required></label><br>
    <label>Last name<br><input type="text" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required></label><br>
    <label>Email<br><input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required></label><br>
    <label>Phone<br><input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>"></label><br>
    <label>Account type<br>
      <select name="account_type">
        <option value="savings" <?php echo ($student['account_type']=='savings')?'selected':''; ?>>Savings</option>
        <option value="current" <?php echo ($student['account_type']=='current')?'selected':''; ?>>Current</option>
        <option value="fixed" <?php echo ($student['account_type']=='fixed')?'selected':''; ?>>Fixed</option>
      </select>
    </label><br>
    <label>Username<br><input type="text" name="username" value="<?php echo htmlspecialchars($student['username']); ?>" required></label><br>
    <button type="submit">Update</button>
  </form>
  <p><a href="index.php">Back to list</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
