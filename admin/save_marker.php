<?php
session_name('admin_session');
session_start();
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $radius = $_POST['radius'];

    // Check if the admin is logged in and the session variable is set
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];

        // Get the current date and time
        $currentDateTime = date("Y-m-d H:i:s");

        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("INSERT INTO restricted_area (latitude, longitude, location_name, description, radius, admin_id, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $latitude, $longitude, $locationName, $description, $radius, $admin_id, $currentDateTime);

        if ($stmt->execute()) {
            echo "Marker data stored successfully";
        } else {
            echo 'Error: ' . $stmt->error;
        }

        $stmt->close();
        exit;
    } else {
        echo "Error: Admin not logged in";
        exit;
    }
}

$con->close();
?>
