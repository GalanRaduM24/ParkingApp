<?php
    include "../php/inc/header.php";

    require '../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();

    $parkingSpotName = $_GET['name'];

    $host = getenv('DB_HOST');
    $db = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form has been submitted
    
    // Handle the booking confirmation
    if (isset($_POST['confirm'])) {
        // Perform the database update
        $updateSql = "UPDATE parkings SET locuri = locuri - 1 WHERE name = '$parkingSpotName'";
        
        if ($conn->query($updateSql) === TRUE) {
            echo '<script>';
            echo 'console.log("Booking confirmed for: ' . $parkingSpotName . '");';
            echo '</script>';
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
    
?>

<div class="container">
   <div class="col-md-12">
       <h1>Booking Page</h1>
    <p>Are you booking for: <?php echo $parkingSpotName; ?></p>
    
        <form method="Post">   
        <input type="hidden" name="parkingSpotName" value="<?php echo $parkingSpotName; ?>">
        <button onclick="confirmBooking()" type="submit" name="confirm" class="yes-button">Yes</button>
        <button onclick="cancelBooking()" type="submit" class="no-button">No</button>
       </form>

    <script>
        function confirmBooking() {
            // Add your logic here for handling the booking confirmation
            alert("Booking confirmed for: <?php echo $parkingSpotName; ?>");
            
        }

        function cancelBooking() {
            // Add your logic here for handling the booking cancellation
            alert("Cancel booking acction for: <?php echo $parkingSpotName; ?>");
        }
       </script>
   
    <?php
       
       echo "<br>";
       
        $sql = "SELECT * FROM parkings";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                if($row["name"] === $parkingSpotName){
                echo "Name: " . $row["name"] . "<br>";
                echo "Locuri: " . $row["locuri"] . "<br>";
                echo "<br>";
                }
            }
        } else {
            echo "No data available";
        }
    ?>

    </div>    
</div>

<div><br></div>
<footer class="fixed-footer">
<?php
    include "../php/inc/footer.php";

    $conn->close();
?>
