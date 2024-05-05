<?php
include_once 'connection.php';

if (isset($_POST['user_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $select_query = "SELECT id, username, password FROM user WHERE username='$username'";
    $result = mysqli_query($con, $select_query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $hashed_password = $row['password'];

        // Verify the entered password against the hashed password
        if (password_verify(trim($password), $hashed_password)) {
            // Store user ID in the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "<script>alert('Incorrect password')</script>";
        }
    } else {
        echo "<script>alert('User not found')</script>";
    }
}
?>
