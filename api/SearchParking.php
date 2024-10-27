<?php
    include "../api/inc/header.php";
?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
    </style>
        <script src="../js/script.js"></script>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  
  <div class="container">
   <div class="col-md-12">
         <h1>Search for parking lot</h1>
       <input type="text" id="searchInput" placeholder="Search parking spot">
    <button id="searchButton" class="search-button">Search</button>
    <br>
    <div id="map"></div>
    </div>
    <br>
    <p id="clickedParkingMessage"></p>
<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);

if ($isLoggedIn) {
    // User is logged in, display the bookButton link
    echo '<a href="Booking.php" id="bookButton" style="width: 200px; height: 40px;"></a>';
} else {
    // User is not logged in, display a message or redirect to the login page
    echo 'Please <a href="Profile.php">login</a> to book a parking slot.';
}
?>
</div>
<div><br></div>
<footer>
<?php
    include "../api/inc/footer.php";
?>
