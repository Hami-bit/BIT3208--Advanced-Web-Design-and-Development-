<?php
require_once __DIR__ . '/includes/session.php';
header('Content-Type: text/plain');
var_export($_SESSION);
?>
