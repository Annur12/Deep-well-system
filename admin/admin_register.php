<?php
include_once '../connection.php';

if (isset($_POST['admin_register'])) {
    // Get and sanitize admin registration data

    $username = $_POST['username'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $email = $_POST['email'];

    // Check if the admin username or email already exists in the admin table
    $select_query = "SELECT * FROM admin WHERE username='$username' OR email='$email'";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);

    if ($rows_count > 0) {
        echo "<script>alert('Admin user already exists')</script>";
    } else if ($password != $conf_password) {
        echo "<script>alert('Password does not match')</script>";
    } else {
        // Hash the admin password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin data into the admin table
        $insert_query = "INSERT INTO admin (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        $sql_execute = mysqli_query($con, $insert_query);

        if ($sql_execute) {

            echo "<script>alert('Registration successful.')</script>";

            echo "<script>setTimeout(function(){ window.location.href = 'admin_login.php'; }, 1000);</script>";

            exit;
        } else {
            echo "<script>alert('Error in registration.')</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-sizing: border-box;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            text-align: left;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .icon {
            margin-right: 8px;
        }

        .register-link {
            color: #000;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }

        .register-link:hover {
            text-decoration: underline;
            color: #007BFF;
        }
    </style>
</head>
<body>

<div class="container">
    <h2> Admin Registration</h2>
    <form action="#" method="post">
        <label for="username"><i class="fas fa-user icon"></i> Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="username"><i class="fas fa-envelope icon"></i> email:</label>
        <input type="text" id="email" name="email" required>

        <label for="password"><i class="fas fa-lock icon"></i> Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="password"><i class="fas fa-lock icon"></i> Confirm Password:</label>
        <input type="password" id="password" name="conf_password" required>

        <button type="submit" name="admin_register">Register</button>
        <span>Already have an account?<a href="admin_login.php" class="register-link">Login</a></span>
    </form>
</div>

</body>
</html>