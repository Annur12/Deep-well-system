<?php
include_once '../connection.php';

// Fetch user markers from the database
$sql = "SELECT * FROM use_markers";
$result = $con->query($sql);

$markers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = [
            'id' => $row['id'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'name' => $row['name'],
            'contact_no' => $row['contact_no'],
            'markerType' => $row['markerType'],
            'water_quality' => $row['water_quality'],
            'depth' => $row['depth'],
            'location' => $row['location'],
            'status' => $row['status'],
            'date' => $row['date'],
        ];
    }
}

$con->close();

header('Content-Type: application/json');
echo json_encode($markers);
?>