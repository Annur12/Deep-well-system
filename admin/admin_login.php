<?php
session_name('admin_session');
session_start();
include_once '../connection.php';

if (isset($_POST['admin_login'])) {
    // Get and sanitize admin login data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the admin username exists in the admin table
    $select_query = "SELECT id, username, password, role FROM admin WHERE username='$username'";
    $result = mysqli_query($con, $select_query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $hashed_password = $row['password'];

        if (password_verify(trim($password), $hashed_password)) {
            // Set admin session variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on roles
            if ($_SESSION['role'] === 'approval') {
                header('Location: map.php');
                exit;
            } elseif ($_SESSION['role'] === 'admin') {
                // Redirect to the admin dashboard or another page
                header('Location: dashboard.php');
                exit;
            } else {
                // Handle other roles or show an error message
                echo "<script>alert('Invalid role.')</script>";
            }
        } else {
            echo "<script>alert('Incorrect password')</script>";
        }
    } else {
        echo "<script>alert('Admin user not found')</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
    <h2> Admin Login</h2>
    <form action="#" method="post">
        <label for="username"><i class="fas fa-user icon"></i> Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password"><i class="fas fa-lock icon"></i> Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="admin_login">Login</button>
        <span>Don't have an account?<a href="admin_register.php" class="register-link">Register</a></span>
    </form>
</div>

</body>
</html>