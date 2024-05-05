<?php
include_once 'connection.php';

if (isset($_POST['user_register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];

    $select_query = "SELECT * FROM user WHERE username='$username' OR email='$email'";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);

    if ($rows_count > 0) {
        echo "<script>alert('User is already exist')</script>";
    } else if ($password != $conf_password) {
        echo "<script>alert('Password does not match')</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO user (username, password, location, email, contact_no)
        VALUES ('$username', '$hashed_password', '$location', '$email', '$contact_no')";

        $sql_execute = mysqli_query($con, $insert_query);

        if ($sql_execute) {
            // Store user ID in the session
            $_SESSION['user_id'] = mysqli_insert_id($con);
            $_SESSION['success_message'] = "You are successfully registered.";
            header('Location: homepage.php');
            exit;
        } else {
            echo "<script>alert('Error in registration.')</script>";
        }
    }
}
?>
