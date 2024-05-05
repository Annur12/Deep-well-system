<?php
include_once '../connection.php';

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

    // Perform the delete operation
    $delete_user = "DELETE FROM user WHERE id = $user_id";
    mysqli_query($con, $delete_user);

    // Redirect back to the page displaying the list
    header("Location: user_table.php");
    exit();
}
?>
