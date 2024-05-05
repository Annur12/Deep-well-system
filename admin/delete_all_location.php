<?php
include_once '../connection.php';

if (isset($_GET['delete_all'])) {
    // Perform the delete all operation
    $delete_all_location = "DELETE FROM barangay";
    mysqli_query($con, $delete_all_location);

    // Redirect back to the page displaying the list
    header("Location: barangay_table.php");
    exit();
}
?>
