<?php 

include_once '../connection.php';
      
?>
<button class="open-btn" onclick="toggleSidebar()">&#9776;</button>

<div class="sidebar" id="sidebar">
  <a href="dashboard.php" onclick="toggleSidebar()"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="map.php" onclick="toggleSidebar()"><i class="fas fa-map"></i> Map</a>
  <a href="restricted_area_table.php" onclick="toggleSidebar()"><i class="fas fa-exclamation-triangle"></i> Restricted Area</a>
  <div class="dropdown">
    <a class="dropbtn" onclick="toggleDropdown('barangayDropdown')"><i class="fas fa-map-marker-alt"></i> Select Location &#9660;</a>
    <div class="dropdown-content" id="barangayDropdown">
      <?php 
      $select_brangay_names = "SELECT * FROM barangay";
      $result_barangay_names = mysqli_query($con, $select_brangay_names);

      if($result_barangay_names) {
        while($row_barangay = mysqli_fetch_assoc($result_barangay_names)) {
          $barangay_name = $row_barangay['barangay_name'];
          $longitude = $row_barangay['longitude'];
          $latitude = $row_barangay['latitude'];
          echo "<a href='#' data-latitude='$latitude' data-longitude='$longitude'><i class='fas fa-map-marker'></i> $barangay_name</a>";

        } 
      }  else {

          echo "Error: " . mysqli_error($con);
      }
      ?>
    </div>
  </div>
  <div class="dropdown">
    <a class="dropbtn" onclick="toggleDropdown('operationDropdown')"><i class="fas fa-cogs"></i> Location &#9660;</a>
    <div class="dropdown-content" id="operationDropdown">
      <a href="add_barangay.php"><i class="fas fa-plus-circle"></i> Add Location</a>
      <a href="barangay_table.php"><i class="fas fa-list"></i> List of Location</a>
    </div>
  </div>
  <a href="marker_table.php" onclick="toggleSidebar()"><i class="fas fa-map-pin"></i> Applicant</a>
  <a href="user_table.php" onclick="toggleSidebar()"><i class="fas fa-user"></i> User</a>
  <a href="archived.php" onclick="toggleSidebar()"><i class="fas fa-archive"></i> Archive</a>
  <a href="edit_account.php" onclick="toggleSidebar()"><i class="fas fa-cog"></i> Setting</a>
  <a href="admin_logout.php" onclick="toggleSidebar()" style="margin-top: auto;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>