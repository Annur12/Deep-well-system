<?php
session_name('admin_session');
session_start();

// Check if admin session variables are set
if (isset($_SESSION['admin_id'])) {
    // Unset admin session variables
    unset($_SESSION['admin_id']);
}

// Destroy the session
session_destroy();

// Redirect to the admin login page
header('Location: admin_login.php');
exit;
?>