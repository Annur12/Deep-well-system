<?php
// delete_marker.php
include_once '../connection.php';

// Retrieve latitude and longitude from the POST request
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Prepare and execute a DELETE query to remove the marker from the database
$sql = "DELETE FROM restricted_area WHERE latitude = $latitude AND longitude = $longitude";

if ($con->query($sql) === TRUE) {
    echo "'OK' to delete the marker";
} else {
    echo "Error deleting marker: " . $con->error;
}

$con->close();
?>
