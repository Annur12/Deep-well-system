<?php
include_once '../connection.php';

if (isset($_GET['delete_marker'])) {
    $user_id = $_GET['delete_marker'];

    // Perform the delete operation
    $delete_location = "DELETE FROM use_markers WHERE id = $user_id";
    mysqli_query($con, $delete_location);

    // Redirect back to the page displaying the list
    header("Location: marker_table.php");
    exit();
}
?>
