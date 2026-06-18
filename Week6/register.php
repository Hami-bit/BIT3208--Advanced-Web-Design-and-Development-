<?php require_once __DIR__ . '/includes/header.php'; ?>

<main>
  <h1>Add Bank User</h1>
  <form action="create.php" method="post">
    <label>First name<br><input type="text" name="first_name" required></label><br>
    <label>Last name<br><input type="text" name="last_name" required></label><br>
    <label>Email<br><input type="email" name="email" required></label><br>
    <label>Phone<br><input type="text" name="phone"></label><br>
    <label>Account type<br>
      <select name="account_type">
        <option value="savings">Savings</option>
        <option value="current">Current</option>
        <option value="fixed">Fixed</option>
      </select>
    </label><br>
    <label>Username<br><input type="text" name="username" required></label><br>
    <label>Password<br><input type="password" name="password" required></label><br>
    <button type="submit">Create User</button>
  </form>
  <p><a href="index.php">Back to list</a></p>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
