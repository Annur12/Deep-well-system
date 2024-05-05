
<?php

session_start();
include_once 'header.php';
include_once 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page or display a message
    header('Location: admin_login.php');
    exit;
}
?>

<div id="map"></div>

<div class="marker-container">
    <div class="marker-divider vertical-center">
        <div class="marker-color marker-green"></div>
        <p>Applicant</p>
    </div>

    <div class="marker-divider vertical-center">
        <div class="marker-color marker-red"></div>
        <p>Approved</p>
    </div>

    <div class="marker-divider vertical-center">
        <div class="marker-color marker-black"></div>
        <p>Restricted Area</p>
    </div>
</div>


<?php
include_once 'footer.php';
?>