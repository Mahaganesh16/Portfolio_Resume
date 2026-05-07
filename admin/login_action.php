<?php
session_set_cookie_params(0, '/');
session_start();

// In a real application, use a database and password_hash
$valid_username = 'admin';
$valid_password = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $valid_username && $pass === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        setcookie('admin_auth', md5('logged_in_success'), time() + (86400 * 30), "/"); 
        header('Location: index');
        exit;
    } else {
        header('Location: login?error=1');
        exit;
    }
} else {
    header('Location: login');
    exit;
}
