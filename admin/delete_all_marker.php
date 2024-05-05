<?php
include_once '../connection.php';

if (isset($_GET['delete_all'])) {
    // Perform the delete all operation
    $delete_all_marker = "DELETE FROM use_markers";
    mysqli_query($con, $delete_all_marker);

    // Redirect back to the page displaying the list
    header("Location: marker_table.php");
    exit();
}
?>
