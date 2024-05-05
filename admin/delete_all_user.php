<?php
include_once '../connection.php';

if (isset($_GET['delete_all'])) {
    // Perform the delete all operation
    $delete_all_user = "DELETE FROM user";
    mysqli_query($con, $delete_all_user);

    // Redirect back to the page displaying the list
    header("Location: user_table.php");
    exit();
}
?>
