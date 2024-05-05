<?php
// get_markers.php
include_once 'connection.php';

$sql = "SELECT * FROM restricted_area";
$result = $con->query($sql);

$markers = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = $row;
    }
}

echo json_encode($markers);

$con->close();
?>
