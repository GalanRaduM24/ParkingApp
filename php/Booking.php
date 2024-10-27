<?php
include "../php/inc/header.php";

require '../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// Supabase credentials from .env
$supabaseUrl = getenv('DB_URL');
$supabaseKey = getenv('DB_KEY');

// Create a Guzzle HTTP client
$client = new GuzzleHttp\Client([
    'base_uri' => $supabaseUrl,
    'headers' => [
        'apikey' => $supabaseKey,
        'Authorization' => 'Bearer ' . $supabaseKey,
        'Content-Type' => 'application/json',
    ]
]);

$parkingSpotName = $_GET['name'];
$currentLocuri = 0;  // To hold the current locuri count

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle the booking confirmation
    if (isset($_POST['confirm'])) {
        // Fetch the current locuri value first
        try {
            $response = $client->request('GET', '/rest/v1/parkings?name=eq.' . $parkingSpotName);
            $data = json_decode($response->getBody(), true);

            if (!empty($data)) {
                // Get the current number of available spots
                $currentLocuri = $data[0]['locuri'];
                
                // Check if there are spots available to decrement
                if ($currentLocuri > 0) {
                    // Decrement available spots
                    $newLocuri = $currentLocuri - 1;

                    // Update the record in Supabase
                    $updateResponse = $client->request('PATCH', '/rest/v1/parkings?name=eq.' . $parkingSpotName, [
                        'json' => [
                            'locuri' => $newLocuri
                        ]
                    ]);

                    if ($updateResponse->getStatusCode() === 204) {
                        echo '<script>';
                        echo 'console.log("Booking confirmed for: ' . htmlspecialchars($parkingSpotName) . '");';
                        echo '</script>';
                    } else {
                        echo "Error updating record: " . $updateResponse->getBody();
                    }
                } else {
                    echo "No available spots to book.";
                }
            } else {
                echo "No data available for the specified parking spot.";
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

// Fetch parking spot details
try {
    $response = $client->request('GET', '/rest/v1/parkings?name=eq.' . $parkingSpotName);
    $data = json_decode($response->getBody(), true);

    if (!empty($data)) {
        // Display parking spot details
        echo "<div class='container'>";
        echo "<div class='col-md-12'>";
        echo "<h1>Booking Page</h1>";
        echo "<p>Are you booking for: " . htmlspecialchars($parkingSpotName) . "</p>";
        echo "<form method='POST'>";   
        echo "<input type='hidden' name='parkingSpotName' value='" . htmlspecialchars($parkingSpotName) . "'>";
        echo "<button onclick='confirmBooking()' type='submit' name='confirm' class='yes-button'>Yes</button>";
        echo "<button onclick='cancelBooking()' type='button' class='no-button'>No</button>";
        echo "</form>";
        
        // JavaScript for alerts
        echo "<script>
            function confirmBooking() {
                alert('Booking confirmed for: " . htmlspecialchars($parkingSpotName) . "');
            }
            function cancelBooking() {
                alert('Cancel booking action for: " . htmlspecialchars($parkingSpotName) . "');
            }
        </script>";

        // Display parking spot details
        foreach ($data as $row) {
            echo "Name: " . htmlspecialchars($row["name"]) . "<br>";
            echo "Locuri: " . htmlspecialchars($row["locuri"]) . "<br><br>";
        }

        echo "</div></div>";
    } else {
        echo "No data available";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<div><br></div>
<footer class="fixed-footer">
<?php
include "../php/inc/footer.php";
?>
