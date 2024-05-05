<?php
// store_marker.php
include_once 'connection.php';
session_name('user_session');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact_no = $_POST['contact_no'];
    $location = $_POST['location'];
    $markerType = $_POST['markerType'];
    $water_quality = $_POST['water_quality'];
    $water_depth = $_POST['depth'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Get the user ID from the session (assuming you store user ID in $_SESSION['user_id'])
    $userId = $_SESSION['user_id'];

    $status = "Pending";

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO use_markers (name, contact_no, location, markerType, water_quality, depth, latitude, longitude, status, user_id, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssssssi", $name, $contact_no, $location, $markerType, $water_quality, $water_depth, $latitude, $longitude, $status, $userId);

    if ($stmt->execute()) {
        echo "Marker data stored successfully";
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    exit;
}

$con->close();
?>
