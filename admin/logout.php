<?php
session_set_cookie_params(0, '/');
session_start();

// Clear session
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();

// Clear fallback cookie
setcookie('admin_auth', '', time() - 3600, "/");

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header('Location: login');
exit;
