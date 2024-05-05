<?php
include_once '../connection.php';

if (isset($_GET['delete_location'])) {
    $location_id = $_GET['delete_location'];

    // Perform the delete operation
    $delete_location = "DELETE FROM barangay WHERE id = $location_id";
    mysqli_query($con, $delete_location);

    // Redirect back to the page displaying the list
    header("Location: barangay_table.php");
    exit();
}
?>
