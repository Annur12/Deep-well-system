<?php
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];
    $location_latitude = $_POST['latitude'];
    $location_longitude = $_POST['longitude'];

    // Perform the update operation
    $update_query = "UPDATE barangay SET
                     barangay_name = '$location_name',
                     latitude = '$location_latitude',
                     longitude = '$location_longitude'
                     WHERE id = $location_id";

    mysqli_query($con, $update_query);

    // Set a flag in the session to indicate a successful update
    session_start();
    $_SESSION['update_success'] = true;

    // Redirect back to the page displaying the list
    header("Location: barangay_table.php");
    exit();
}
?>
