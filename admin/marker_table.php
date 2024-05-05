<?php
include_once 'header.php';
include_once 'sidebar.php';
include_once '../connection.php';
// Pagination settings
$rowsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

$select_barangay = "SELECT * FROM use_markers LIMIT $offset, $rowsPerPage";
$result = mysqli_query($con, $select_barangay);
$number = $offset;
?>

<div class="content">
    <div class="table-container">
    <?php
        $number = 0;
        if (mysqli_num_rows($result) > 0) {
        ?>
        <h2>List of User Markers</h2>

        <!-- Table to display Barangay, Longitude, Latitude, and Delete icon -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Depth</th>
                    <th>Water Quality</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th> <!-- Add a new column for the delete icon -->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_id = $row['id'];
                    $name = $row['name'];
                    $location = $row['location'];
                    $markerType = $row['markerType'];
                    $water_quality = $row['water_quality'];
                    $depth = $row['depth'];
                    $latitude = $row['latitude'];
                    $longitude = $row['longitude'];
                    $status = $row['status'];
                    $date = $row['date'];
                    $number++;
                ?>
                    <tr>
                        <td><?php echo $number; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $location; ?></td>
                        <td><?php echo $markerType; ?></td>
                        <td><?php echo $longitude; ?></td>
                        <td><?php echo $latitude; ?></td>
                        <td><?php echo $depth; ?></td>
                        <td><?php echo $water_quality; ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $date; ?></td>
                        <td>
                            <a href="delete_marker.php?delete_marker=<?php echo $user_id; ?>" class="delete-icon" title="Delete" onclick="return confirmDelete();"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td colspan="10"></td>
                        <td>
                            <a href="delete_all_marker.php?delete_all" class="delete-all-btn" onclick="return confirmDeleteAll();">Delete All</a>
                        </td>
                    </tr>
            </tbody>
        </table>

        <!-- Add back button if not on the first page -->
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo ($page - 1); ?>" class="pagination-button">Back</a>
        <?php endif; ?>

        <!-- Add next button if there are more pages -->
        <?php
        $nextPage = $page + 1;
        $selectCount = "SELECT COUNT(*) AS total FROM use_markers";
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
