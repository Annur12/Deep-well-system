<?php
session_name('admin_session');
session_start();
include_once 'header.php';
include_once 'sidebar.php';
include_once '../connection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch current user data from the database
$userID = $_SESSION['admin_id'];
$selectUserQuery = "SELECT username, email FROM admin WHERE id=$userID";
$result = mysqli_query($con, $selectUserQuery);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $currentUsername = $row['username'];
    $currentEmail = $row['email'];
} else {
    echo "<script>alert('Error fetching user data.')</script>";
    // Optionally, you can redirect or handle the error in another way
    exit;
}

// Example code for updating the password
if (isset($_POST['submit'])) {
    // Get and sanitize updated user data
    $newPassword = $_POST['password'];

    // Hash the new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user data in the database
    $updateQuery = "UPDATE admin SET password='$newHashedPassword' WHERE id=$userID";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Account updated successfully.')</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'dashboard.php'; }, 1000);</script>";
        // Optionally, you can redirect to another page after updating
    } else {
        echo "<script>alert('Error updating password.')</script>";
    }
}
?>

<div class="content">
    <h2>Edit Account</h2>

    <!-- Form for editing account information -->
    <form action="#" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $currentUsername; ?>" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $currentEmail; ?>" required>

        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>

        <button class="barangay-button" name="submit" type="submit">Save</button>
    </form>
</div>

<?php include_once 'footer.php'?>
