<?php
session_name('admin_session');
session_start();

include_once 'header.php';
include_once 'sidebar.php';
include_once '../connection.php'; // Make sure to include your database connection file

if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page or display a message
    header('Location: admin_login.php');
    exit;
}

// Fetch counts from the database
$totalUsersQuery = "SELECT COUNT(*) AS totalUsers FROM user";
$totalMarkersQuery = "SELECT COUNT(*) AS totalMarkers FROM use_markers";
$totalApprovedQuery = "SELECT COUNT(*) AS totalApproved FROM use_markers WHERE status = 'Approved'";
$totalPendingQuery = "SELECT COUNT(*) AS totalPending FROM use_markers WHERE status = 'Pending'";

$totalUsersResult = mysqli_query($con, $totalUsersQuery);
$totalMarkersResult = mysqli_query($con, $totalMarkersQuery);
$totalApprovedResult = mysqli_query($con, $totalApprovedQuery);
$totalPendingResult = mysqli_query($con, $totalPendingQuery);

$totalUsers = mysqli_fetch_assoc($totalUsersResult)['totalUsers'];
$totalMarkers = mysqli_fetch_assoc($totalMarkersResult)['totalMarkers'];
$totalApproved = mysqli_fetch_assoc($totalApprovedResult)['totalApproved'];
$totalPending = mysqli_fetch_assoc($totalPendingResult)['totalPending'];


$monthlyUsersQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM user GROUP BY month";
$monthlyMarkersQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM use_markers GROUP BY month";
$monthlyResidentialMarkersQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM use_markers WHERE markerType = 'Residential' GROUP BY month";
$monthlyCommercialMarkersQuery = "SELECT MONTH(date) AS month, COUNT(*) AS count FROM use_markers WHERE markerType = 'Commercial' GROUP BY month";

$monthlyUsersResult = mysqli_query($con, $monthlyUsersQuery);
$monthlyMarkersResult = mysqli_query($con, $monthlyMarkersQuery);
$monthlyResidentialMarkersResult = mysqli_query($con, $monthlyResidentialMarkersQuery);
$monthlyCommercialMarkersResult = mysqli_query($con, $monthlyCommercialMarkersQuery);

// Initialize arrays to store monthly data
$userMonths = [];
$markerMonths = [];
$residentialMonths = [];
$commercialMonths = [];
$userCounts = [];
$markerCounts = [];
$residentialCounts = [];
$commercialCounts = [];

// Fetch and organize data for monthly Users
while ($row = mysqli_fetch_assoc($monthlyUsersResult)) {
    $monthNumber = $row['month'];
    $monthName = DateTime::createFromFormat('!m', $monthNumber)->format('F');
    $userMonths[] = $monthName; // Full month name (e.g., January)
    $userCounts[] = $row['count'];
}

// Fetch and organize data for monthly Markers
while ($row = mysqli_fetch_assoc($monthlyMarkersResult)) {
    $monthNumber = $row['month'];
    $monthName = DateTime::createFromFormat('!m', $monthNumber)->format('F');
    $markerMonths[] = $monthName; // Full month name (e.g., January)
    $markerCounts[] = $row['count'];
}

// Fetch and organize data for monthly Residential markers
while ($row = mysqli_fetch_assoc($monthlyResidentialMarkersResult)) {
    $monthNumber = $row['month'];
    $monthName = DateTime::createFromFormat('!m', $monthNumber)->format('F');
    $residentialMonths[] = $monthName; // Full month name (e.g., January)
    $residentialCounts[] = $row['count'];
}

// Fetch and organize data for monthly Commercial markers
while ($row = mysqli_fetch_assoc($monthlyCommercialMarkersResult)) {
    $monthNumber = $row['month'];
    $monthName = DateTime::createFromFormat('!m', $monthNumber)->format('F');
    $commercialMonths[] = $monthName; // Full month name (e.g., January)
    $commercialCounts[] = $row['count'];
}

$barangayApprovedMarkersQuery = "SELECT location, COUNT(*) AS approvedCount FROM use_markers WHERE status = 'Approved' GROUP BY location";
$barangayApprovedMarkersResult = mysqli_query($con, $barangayApprovedMarkersQuery);

// Initialize arrays to store barangay data
$barangays = [];
$approvedCounts = [];

// Fetch and organize data for approved markers in each barangay
while ($row = mysqli_fetch_assoc($barangayApprovedMarkersResult)) {
    $barangays[] = $row['location'];
    $approvedCounts[] = $row['approvedCount'];
}

?>

<div class="content">
    <div class="dashboard">
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h2>Deep Well Water Source</h2>
                <p>Welcome to your dashboard</p>
            </div>

            <!-- Three Main Cards -->
            <div class="dashboard-section">
                <div class="dashboard-card">
                    <i class="fas fa-users card-icon"></i>
                    <div class="card-title">Total Applicants</div>
                    <div class="card-value"><?php echo $totalUsers; ?></div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-map-marker-alt card-icon"></i>
                    <div class="card-title">Total Markers</div>
                    <div class="card-value"><?php echo $totalMarkers; ?></div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-check-circle card-icon" style="color: #28a745;"></i>
                    <div class="card-title">Approved</div>
                    <div class="card-value"><?php echo $totalApproved; ?></div>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-clock card-icon" style="color: #ffc107;"></i>
                    <div class="card-title">Pending</div>
                    <div class="card-value"><?php echo $totalPending; ?></div>
                </div>
            </div>
            <div class="dashboard-section">
                <canvas id="monthlyChart" width="400" height="150"></canvas>
            </div>
            <div class="dashboard-section">
                <canvas id="barangayChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    var monthlyChartData = {
        labels: <?php echo json_encode($userMonths); ?>,
        datasets: [{
                label: 'Users',
                data: <?php echo json_encode($userCounts); ?>,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 10,
                lineTension: 0.3
            },
            {
                label: 'Markers',
                data: <?php echo json_encode($markerCounts); ?>,
                fill: false,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 10,
                lineTension: 0.3
            },
            {
                label: 'Commercial',
                data: <?php echo json_encode($commercialCounts); ?>,
                fill: false,
                borderColor: 'rgba(255, 205, 86, 1)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 10,
                lineTension: 0.3
            },
            {
                label: 'Residential',
                data: <?php echo json_encode($residentialCounts); ?>,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 10,
                lineTension: 0.3
            }
        ]
    };

    // Get the canvas element
    var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');

    // Create a line chart for Monthly Counts of Users, Markers, Commercial, and Residential
    var monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: monthlyChartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Counts of Users, Markers, Commercial, and Residential',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });

    var barangayChartData = {
        labels: <?php echo json_encode($barangays); ?>,
        datasets: [{
            label: 'Approved Markers',
            data: <?php echo json_encode($approvedCounts); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Get the canvas element
    var barangayCtx = document.getElementById('barangayChart').getContext('2d');

    // Create a bar chart for Approved Markers in Every Barangay
    var barangayChart = new Chart(barangayCtx, {
        type: 'bar',
        data: barangayChartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Number of Approved Markers in Every Barangay',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });

</script>

<?php include_once 'footer.php' ?>
