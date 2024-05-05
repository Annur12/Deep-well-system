<?php
// Set the session name for user
session_name('user_session');
session_start();

// Check if user session variables are set
if (isset($_SESSION['user_id'])) {
    // Destroy the user session
    session_destroy();
}

// Redirect the user to the homepage or login page
header("Location: homepage.php");
exit();
?>
