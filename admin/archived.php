<?php
include_once 'header.php';
include_once 'sidebar.php';
include_once '../connection.php';

// Pagination settings
$rowsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

// Handle Search and Filter
$searchArchived = isset($_GET['search_archived']) ? mysqli_real_escape_string($con, $_GET['search_archived']) : '';
$filterArchived = isset($_GET['filter_archived']) ? mysqli_real_escape_string($con, $_GET['filter_archived']) : '';

$whereClauseArchived = '';

if (!empty($searchArchived)) {
    $whereClauseArchived .= " AND (location_name LIKE '%$searchArchived%' OR radius LIKE '%$searchArchived%' OR latitude LIKE '%$searchArchived%' OR longitude LIKE '%$searchArchived%')";
}

if (!empty($filterArchived)) {
    // Add additional conditions based on the filter criteria
    // Modify as per your filter requirements
    // Example: $whereClauseArchived .= " AND some_column = '$filterArchived'";
}

// Select archived restricted areas based on search, filter, and pagination
$selectArchived = "SELECT * FROM archived_restricted_area WHERE 1 $whereClauseArchived LIMIT $offset, $rowsPerPage";
$resultArchived = mysqli_query($con, $selectArchived);
$numberArchived = $offset;
?>

<div class="content">
    <div class="table-container">
        <h2>Archive</h2>

        <!-- Search and Filter Form -->
        <form action="archive.php" method="GET" class="search-container">
    <input type="text" name="search_archived" placeholder="Search" class="search-input" value="<?php echo $searchArchived; ?>">
    <!-- Add filter options as needed -->
    <!-- Example: <select name="filter_archived">
                    <option value="option1" <?php echo ($filterArchived == 'option1') ? 'selected' : ''; ?>>Option 1</option>
                    <option value="option2" <?php echo ($filterArchived == 'option2') ? 'selected' : ''; ?>>Option 2</option>
                </select> -->
    <button type="submit" class="search-button">Search</button>
</form>

        <!-- Table to display Archived Restricted Areas -->
        <?php if (mysqli_num_rows($resultArchived) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Location</th>
                        <th>Radius (in meter/s)</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rowArchived = mysqli_fetch_assoc($resultArchived)) {
                        $numberArchived++;
                        ?>
                        <tr>
                            <td><?php echo $numberArchived; ?></td>
                            <td><?php echo $rowArchived['location_name']; ?></td>
                            <td><?php echo $rowArchived['radius']; ?></td>
                            <td><?php echo $rowArchived['latitude']; ?></td>
                            <td><?php echo $rowArchived['longitude']; ?></td>
                            <td>
                                <!-- Add restore button logic here -->
                             <a href="restore_archived.php?restore_id=<?php echo $rowArchived['id']; ?>" class="restore-icon" title="Restore" onclick="return confirmRestore();"><i class="fas fa-undo"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No archived data available</p>
        <?php } ?>

        <!-- Add next button if there are more pages -->
        <?php
        $nextPageArchived = $page + 1;
        $selectCountArchived = "SELECT COUNT(*) AS total FROM archived_restricted_area WHERE 1 $whereClauseArchived";
        $countResultArchived = mysqli_query($con, $selectCountArchived);
        $rowCountArchived = mysqli_fetch_assoc($countResultArchived)['total'];
        $lastPageArchived = ceil($rowCountArchived / $rowsPerPage);

        if ($nextPageArchived <= $lastPageArchived) {
            echo '<a href="?page=' . $nextPageArchived . '" class="pagination-button">Next</a>';
        }
        ?>

        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo ($page - 1); ?>" class="pagination-button">Back</a>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'footer.php' ?>
