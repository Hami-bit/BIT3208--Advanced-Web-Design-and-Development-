<?php
// Admin authentication helper
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

function admin_is_logged_in() {
    return !empty($_SESSION['admin_id']);
}

function admin_require_login() {
    if (!admin_is_logged_in()) {
        header('Location: /NexaBank_Week7/admin/login.php');
        exit;
    }
}

/**
 * Call this after a successful login to set session data and prevent session fixation.
 * @param array $admin The admin user data from the database.
 */
function admin_login_success($admin) {
    session_regenerate_id(true); // Regenerate session ID
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $_SESSION['admin_role'] = $admin['role'];
}

function admin_get_role() {
    return $_SESSION['admin_role'] ?? null;
}

// Accept either a string or array of allowed roles
function admin_require_role($roles) {
    if (!is_array($roles)) $roles = [$roles];
    $role = admin_get_role();
    if (!$role || !in_array($role, $roles)) {
        http_response_code(403);
        echo "<h2>Access denied</h2><p>You do not have permission to view this page.</p>";
        exit;
    }
}
