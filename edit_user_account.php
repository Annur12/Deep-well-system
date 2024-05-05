<?php
include_once 'connection.php';
include_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit;
}

$userID = $_SESSION['user_id'];
$selectUserQuery = "SELECT username, email, location, contact_no FROM user WHERE id=$userID";
$result = mysqli_query($con, $selectUserQuery);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $currentUsername = $row['username'];
    $currentEmail = $row['email'];
    $currentLocation = $row['location'];
    $currentNumber = $row['contact_no'];
} else {
    echo "<script>alert('Error fetching user data.')</script>";
    exit;
}

if (isset($_POST['submit'])) {
    // sanitize updated user data
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];
    $newLocation = $_POST['location'];
    $newNumber = $_POST['contact-number'];

    // Hash the new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the user data in the database
    $updateQuery = "UPDATE user SET 
                    username='$newUsername',
                    email='$newEmail',
                    password='$newHashedPassword',
                    location='$newLocation',
                    contact_no='$newNumber'
                    WHERE id=$userID";

    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        echo "<script>alert('Account updated successfully.')</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 1000);</script>";
    } else {
        echo "<script>alert('Error updating user data: " . mysqli_error($con) . "')</script>";
    }
}
?>


<div class="edit-account-container">
    <h2>Edit Account</h2>
    <form action="edit_user_account.php" method="post">
    <div class="form-group">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $currentUsername; ?>" readonly>
</div>

<div class="form-group">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $currentEmail; ?>">
</div>

<div class="form-group">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
</div>

<div class="form-group">
    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" name="confirm-password">
</div>

<div class="form-group">
    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo $currentLocation; ?>">
</div>

<div class="form-group">
    <label for="contact-number">Contact Number:</label>
    <input type="tel" id="contact-number" name="contact-number" value="<?php echo $currentNumber; ?>">
</div>


        <button type="submit" name="submit">Update</button>
    </form>
</div>
