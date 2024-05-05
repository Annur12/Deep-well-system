<?php
session_name('admin_session');
session_start();
include_once 'header.php';
include_once 'sidebar.php';
include_once '../connection.php';

// Check if the update was successful
$updateSuccess = isset($_SESSION['update_success']) && $_SESSION['update_success'];

// Clear the session flag
unset($_SESSION['update_success']);

// Pagination settings
$rowsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

$select_barangay = "SELECT * FROM restricted_area LIMIT $offset, $rowsPerPage";
$result = mysqli_query($con, $select_barangay);
$number = $offset;
?>

    <script>
        // Display the pop-up message if the update was successful
        <?php if ($updateSuccess): ?>
            alert('Update successful!');
        <?php endif; ?>
    </script>

<div class="content">
    <div class="table-container">
        <?php
        $number = 0;
        if (mysqli_num_rows($result) > 0) {
        ?>
            <h2>List of Restricted area</h2>

            <!-- Table to display Barangay, Longitude, Latitude, and Delete icon -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Radius(in meter/s)</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $marker_id = $row['id'];
                        $marker_location = $row['location_name'];
                        $description = $row['description'];
                        $marker_radius = $row['radius'];
                        $marker_latitude = $row['latitude'];
                        $marker_longitude = $row['longitude'];
                        $number++;
                    ?>
                        <tr>
                            <td><?php echo $number; ?></td>
                            <td><?php echo $marker_location; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><?php echo $marker_radius; ?></td>
                            <td><?php echo $marker_latitude; ?></td>
                            <td><?php echo $marker_longitude; ?></td>
                            <td>
                                <a href="edit_restricted_area.php?edit_restricted_area=<?php echo $marker_id; ?>" class="edit-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="delete_restricted_area_table.php?delete_restricted_area=<?php echo $marker_id; ?>" class="delete-icon" title="Delete" onclick="return confirmDelete();"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6"></td>
                        <td>
                            <a href="delete_all_restricted_area.php?delete_all" class="delete-all-btn" onclick="return confirmDeleteAll();">Delete All</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <!-- Add next button if there are more pages -->
        <?php
        $nextPage = $page + 1;
        $selectCount = "SELECT COUNT(*) AS total FROM restricted_area";
        $countResult = mysqli_query($con, $selectCount);
        $rowCount = mysqli_fetch_assoc($countResult)['total'];
        $lastPage = ceil($rowCount / $rowsPerPage);

        if ($nextPage <= $lastPage) {
            echo '<a href="?page=' . $nextPage . '" class="pagination-button">Next</a>';
        }
        ?>

        <?php } else { ?>
            <p>No data available</p>
        <?php } ?>

        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo ($page - 1); ?>" class="pagination-button">Back</a>
        <?php endif; ?>

    </div>
</div>


<?php include_once 'footer.php' ?>
