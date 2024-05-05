<?php
include_once '../connection.php';

if (isset($_GET['delete_all'])) {
    // Perform the delete all operation
    $delete_all_restricted = "DELETE FROM restricted_area";
    mysqli_query($con, $delete_all_restricted);

    // Redirect back to the page displaying the list
    header("Location: restricted_area_table.php");
    exit();
}
?>
