<?php
// confirm_user_marker.php
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $markerId = $_POST['id'];
    // Update the status to 'Approved'
    $stmt = $con->prepare("UPDATE use_markers SET status = 'Approved' WHERE id = ?");
    $stmt->bind_param("i", $markerId);

    if ($stmt->execute()) {
        echo "Marker confirmed successfully";
    } else {
        echo 'Error updating marker status: ' . $stmt->error;
    }

    $stmt->close();
    exit;
}

$con->close();
?>
