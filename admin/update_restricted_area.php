<?php
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marker_id = $_POST['marker_id'];
    $marker_location = $_POST['location'];
    $marker_radius = $_POST['radius'];
    $marker_latitude = $_POST['latitude'];
    $marker_longitude = $_POST['longitude'];

    // Perform the update operation
    $update_query = "UPDATE restricted_area SET
                     location_name = '$marker_location',
                     radius = '$marker_radius',
                     latitude = '$marker_latitude',
                     longitude = '$marker_longitude'
                     WHERE id = $marker_id";

    mysqli_query($con, $update_query);

    // Set a flag in the session to indicate a successful update
    session_start();
    $_SESSION['update_success'] = true;

    // Redirect back to the page displaying the list
    header("Location: restricted_area_table.php");
    exit();
}
?>
