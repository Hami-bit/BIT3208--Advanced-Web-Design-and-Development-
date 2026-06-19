<?php
// Centralized session configuration for Week7
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieParams = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
];
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params($cookieParams);
} else {
    session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
}
session_name('nexabank_sid');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

?>
