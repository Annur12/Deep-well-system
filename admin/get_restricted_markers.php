<?php
session_name('admin_session');
session_start();

include_once '../connection.php';

// Fetch markers for the logged-in admin
$admin_id = $_SESSION['admin_id'];
$result = $con->query("SELECT * FROM restricted_area WHERE admin_id = $admin_id");

// Convert the result to an array
$markers = [];
while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
}

// Return the markers as JSON
header('Content-Type: application/json');
echo json_encode($markers);

$con->close();

?>