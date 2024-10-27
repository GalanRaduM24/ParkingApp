<?php
include "../php/inc/header.php";
?>

<div class="container">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1>Map of Parking Lots</h1>

            <iframe src="https://www.google.com/maps/d/u/0/embed?mid=13li0ENeuQ0ndby__SseVLDOdio-G_M0&ehbc=2E312F" width="940" height="680"></iframe>

            <?php
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

            // Fetch parking lots data
            try {
                $response = $client->request('GET', '/rest/v1/parkings');
                $data = json_decode($response->getBody(), true);

                if (!empty($data)) {
                    // Output parking lots
                    foreach ($data as $row) {
                        echo "<p>" . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['locuri']) . " spaces)</p>";
                    }
                } else {
                    echo "<p>No parking lots available.</p>";
                }
            } catch (Exception $e) {
                echo 'Error fetching parking lots: ' . htmlspecialchars($e->getMessage());
            }
            ?>
        </div>
    </div>
</div>
<div><br></div>
<footer>
<?php
include "../php/inc/footer.php";
?>
