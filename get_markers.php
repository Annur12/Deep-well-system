<?php

session_name('user_session');
session_start();
// get_markers.php
include_once 'connection.php';

$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM use_markers WHERE user_id = ?";
$stmt = $con->prepare($sql);

// Check if the prepare statement succeeded
if (!$stmt) {
    die('Error preparing statement: ' . $con->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

// Check if the execution of the query was successful
if (!$result) {
    die('Error executing query: ' . $stmt->error);
}

$markers = array();

while ($row = $result->fetch_assoc()) {
    
    $row['date'] = date('Y-m-d H:i:s', strtotime($row['date']));
    $markers[] = $row;
}

$stmt->close();
$con->close();

header('Content-Type: application/json');

echo json_encode($markers);
?>
